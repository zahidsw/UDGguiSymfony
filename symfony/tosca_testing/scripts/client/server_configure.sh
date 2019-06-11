#!/bin/bash

# echo $new_key > /home/ubuntu/config

screen -d -m -S client iperf -c $server_private -t 480
export $floatingIps=$server_private_floatingIp
echo $server_private_floatingIp > /home/ubuntu/env_var_floating
echo $server_private > /home/ubuntu/env_var_private
echo private_floatingIp store is $server_private_floatingIp
