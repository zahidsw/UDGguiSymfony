#!/bin/sh

#echo $client_shared_int_network_floatingIp

# Find out what interface is the primary interface (one with default route)
DEFAULTINTERFACE=`route | grep '^default' | grep -o '[^ ]*$'`
echo Default interface is $DEFAULTINTERFACE

# Get my floating IP
#env
MYFLOATING=$private_floatingIp
MYPRIVATE=$private
echo My server floating IP is $MYFLOATING
# Get my floating IP
#env
MYFLOATING=$private1_floatingIp
MYPRIVATE=$private
echo My client floating IP is $MYFLOATING
