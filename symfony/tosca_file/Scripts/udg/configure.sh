#!/bin/bash

export DEBIAN_FRONTEND=noninteractive

#su - ubuntu && cd $HOME
echo $USER

echo "moving to ansible dir"
cd iot-vno-automated-deployment/ansible

echo "Running tag <configure> of UDG deployment"
ansible-playbook  main.yaml -i localhost --tags configure -vv
#
# # Does this work consistently?
# getIP ()
# {
#   echo "Getting info from spawned instance"
#   ipv4_address=$(ip -o addr list ens3 | awk '{print $4}' | cut -d/ -f1 | head -1)
#   ipv6_address=$(ip -o addr list ens3 | awk '{print $4}' | cut -d/ -f1 | tail -1)
#   mac_address=$(cat /sys/class/net/ens3/address)
# }
#
# debugParams ()
# {
#   echo "Using vars:"
#   echo "server_name = $server_name"
#   echo "ipv4_address = $ipv4_address"
#   echo "ipv6_address = $ipv6_address"
#   echo "mac_address = $mac_address"
#   echo "client_id = $client_id"
#   echo "client_name = $client_name"
#   echo "cms_id = $cms_id"
#   echo "ipv4_parent_address = $ipv4_parent_address"
#   echo "ipv6_parent_address = $ipv6_parent_address"
# }
#
# setupDB ()
# {
#   echo "Saving the settings into the database"
#   config_set_id=$(echo "SELECT id FROM config_set WHERE name='config_admin';" | mysql --login-path=udg upv6 -N)
#   echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'short_server_name', '$server_name');" | mysql --login-path=udg upv6
#   echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'long_server_name', '$server_name');" | mysql --login-path=udg upv6
#   echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'ipv4_udg', '$ipv4_address');" | mysql --login-path=udg upv6
#   echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'ipv6_udg', '$ipv6_address');" | mysql --login-path=udg upv6
#   echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'mac_address', '$mac_address');" | mysql --login-path=udg upv6
#   echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'ipv4_parent_udg', '$ipv4_parent_address');" | mysql --login-path=udg upv6
#   echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'ipv6_parent_udg', '$ipv6_parent_address');" | mysql --login-path=udg upv6
#
#   # save clients
#   echo "INSERT INTO clients (client_id,client_name) VALUES ('$client_id', '$client_name');" | mysql --login-path=udg upv6
#   echo "INSERT INTO cms (ucid,client_id,ipv6_address,ipv4_address) VALUES ('$cms_id', '$client_id', '$ipv6_address', '$ipv4_address');" | mysql --login-path=udg upv6
#   echo "UPDATE users SET client_id='$client_id' WHERE name='admin';" | mysql --login-path=udg upv6
#   echo "=== Done with database setup"
# }
#
# while [[ $# -gt 0 ]]
# do
#   key="$1"
#
#   case $key in
#       --help)
#       help
#       exit 0
#       ;;
#       -s|--server_name)
#       server_name="$2"
#       shift
#       shift
#       ;;
#       -p|--parent_ipv4)
#       ipv4_parent_address="$2"
#       shift
#       shift
#       ;;
#       -P|--parent_ipv6)
#       ipv6_parent_address="$2"
#       shift
#       shift
#       ;;
#       -m|--mac_address)
#       mac_address="$2"
#       shift
#       shift
#       ;;
#       -i|--client_id)
#       client_id="$2"
#       shift
#       shift
#       ;;
#       -n|--client_name)
#       client_name="$2"
#       shift
#       shift
#       ;;
#       -c|--cms_id)
#       cms_id="$2"
#       shift
#       shift
#       ;;
#       *) # unknown option
#       echo "$1 is an unknown parameter. See help for accepted params:"
#       help
#       exit 0
#       ;;
#   esac
# done
#
# # Constants
# path="/home"
#
# getIP
# debugParams
# setupDB

#echo "hi"
