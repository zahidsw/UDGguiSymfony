#!/bin/sh

if grep -q $(hostname) /etc/hosts
  then next
   else sudo sed -i "s/127.0.0.1 localhost/127.0.0.1 localhost $(hostname)/g" /etc/hosts
fi

if [ $server_shared_int_network_floatingIp ]
then
    screen -d -m -S client iperf -c $server_shared_int_network_floatingIp -t 300
else
    screen -d -m -S client iperf -c $server_private_floatingIp -t 300
fi
echo $client_shared_int_network_floatingIp

# Find out what interface is the primary interface (one with default route)
DEFAULTINTERFACE=`route | grep '^default' | grep -o '[^ ]*$'`
echo Default interface is $DEFAULTINTERFACE

# Get my floating IP
#env
MYFLOATING=$server_private_floatingIp
MYPRIVATE=$private
echo My floating IP for server in client is $MYFLOATING
# Get my floating IP
#env
MYFLOATING=$private1_floatingIp
MYPRIVATE=$private1
echo My floating client IP in client is $MYFLOATING

floating="/home/ubuntu/env_var_floating"
private="/home/ubuntu/env_var_private"
floating=$(cat "$floating") 
private=$(cat "$private") 

sudo /bin/sh -c 'apt-get update'
sudo /bin/sh -c 'apt install -y openvswitch-switch'
sudo /bin/sh -c 'ip link add link '$DEFAULTINTERFACE' type veth peer name ens3-sw'
sudo /bin/sh -c 'ip link set ens3-sw up'
sudo /bin/sh -c 'ovs-vsctl add-br tenantA'
sudo /bin/sh -c 'ovs-vsctl add-port tenantA ens3-sw'
sudo /bin/sh -c 'ovs-vsctl add-port tenantA vxlanA -- set interface vxlanA type=vxlan options:remote_ip='$floating' options:key=5000'
sudo /bin/sh -c 'ovs-vsctl add-port tenantA internalPort -- set interface internalPort type=internal'
sudo /bin/sh -c 'ip addr add '$private'/24 dev internalPort'
sudo /bin/sh -c 'ip link set internalPort up'
echo $(ec2metadata --public-ipv4)
