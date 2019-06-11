#!/bin/bash

# echo $new_key > /home/ubuntu/config

screen -d -m -S server iperf -c $client_private -t 480
echo "hello i am here"
export $floatingIps=$client_private_floatingIp
echo $client_private_floatingIp > /home/ubuntu/env_var_floating
echo $client_private > /home/ubuntu/env_var_private
echo private_floatingIp for servers store is $server_private_floatingIp
