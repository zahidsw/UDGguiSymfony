#!/bin/bash

getIP ()
{
  echo "Getting info from spawned instance"
  ipv4_address=$(ip -o addr list ens3 | awk '{print $4}' | cut -d/ -f1 | head -1)
  ipv6_address='' #$(ip -o addr list ens3 | awk '{print $4}' | cut -d/ -f1 | tail -1)
  mac_address=$(cat /sys/class/net/ens3/address)
}


debugParams ()
{
  echo "Using vars:"
  echo "server_name = $server_name"
  echo "ipv4_address = $ipv4_address"
  echo "ipv6_address = $ipv6_address"
  echo "mac_address = $mac_address"
  echo "client_id = $client_id"
  echo "client_name = $client_name"
  echo "cms_id = $cms_id"
  echo "ipv4_parent_address = $ipv4_parent_address"
  echo "ipv6_parent_address = $ipv6_parent_address"
}

setupDB ()
{
  echo "Saving the settings into the database"
  config_set_id=$(echo "SELECT id FROM config_set WHERE name='config_cc';" | mysql -u root -proot upv6 -N)
 # CASE 1: entries are still not inserterd in DB
  # echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'short_server_name', '$server_name');" | mysql -u root -proot upv6
  # echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'long_server_name', '$server_name');" | mysql -u root -proot upv6
  # echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'ipv4_udg', '$ipv4_address');" | mysql -u root -proot upv6
  # echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'ipv6_udg', '$ipv6_address');" | mysql -u root -proot upv6
  # echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'mac_address', '$mac_address');" | mysql -u root -proot upv6
  # echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'ipv4_parent_udg', '$ipv4_parent_address');" | mysql -u root -proot upv6
  # echo "INSERT INTO config_settings (config_set_id,name,value) VALUES ('$config_set_id', 'ipv6_parent_udg', '$ipv6_parent_address');" | mysql -u root -proot upv6

# CASE 2: entries already there and we just want to update them
  echo "UPDATE config_settings SET value='$ipv4_address' where config_set_id=$config_set_id and name='ipv4_udg';" | mysql -u root -proot upv6
  echo "UPDATE config_settings SET value='$ipv6_address' where config_set_id=$config_set_id and name='ipv6_udg';" | mysql -u root -proot upv6
  echo "UPDATE config_settings SET value='$ipv4_parent_address' where config_set_id=$config_set_id and name='ipv4_parent_udg';" | mysql -u root -proot upv6
  echo "UPDATE config_settings SET value='$ipv6_parent_address' where config_set_id=$config_set_id and name='ipv6_parent_udg';" | mysql -u root -proot upv6
  echo "UPDATE config_settings SET value='$server_name' where config_set_id=$config_set_id and name='short_server_name';" | mysql -u root -proot upv6
  echo "UPDATE config_settings SET value='$server_name' where config_set_id=$config_set_id and name='long_server_name';" | mysql -u root -proot upv6
  echo "UPDATE config_settings SET value='$mac_address' where config_set_id=$config_set_id and name='mac_address';" | mysql -u root -proot upv6

  # save clients
  echo "INSERT INTO clients (client_id,client_name) VALUES ('$client_id', '$client_name');" | mysql -u root -proot upv6
  echo "INSERT INTO cms (ucid,client_id,ipv6_address,ipv4_address) VALUES ('$cms_id', '$client_id', '$ipv6_address', '$ipv4_address');" | mysql -u root -proot upv6
  echo "UPDATE users SET client_id='$client_id' WHERE name='admin';" | mysql -u root -proot upv6
  echo "=== Done with database setup"
}

while [[ $# -gt 0 ]]
do
  key="$1"

  case $key in
      --help)
      help
      exit 0
      ;;
      -s|--server_name)
      server_name="$2"
      shift
      shift
      ;;
      -p|--parent_ipv4)
      ipv4_parent_address="$2"
      shift
      shift
      ;;
      -P|--parent_ipv6)
      ipv6_parent_address="$2"
      shift
      shift
      ;;
      -m|--mac_address)
      mac_address="$2"
      shift
      shift
      ;;
      -i|--client_id)
      client_id="$2"
      shift
      shift
      ;;
      -n|--client_name)
      client_name="$2"
      shift
      shift
      ;;
      -c|--cms_id)
      cms_id="$2"
      shift
      shift
      ;;
      *) # unknown option
      echo "$1 is an unknown parameter. See help for accepted params:"
      help
      exit 0
      ;;
  esac
done

# Constants
# this are configured as env vars by openbaton EMS
# server_name="UDGaaF"
# client_id="UDGaaF_client_id"
# client_name="UDGaaF_client_name"
# cms_id="UDGaaF_cms_id"
# ipv4_parent_address="172.16.100.97"
# ipv6_parent_address="fe80::f816:3eff:fe1a:fe63"

echo "Starting to update DB fields using current environment info.. "
getIP
debugParams
setupDB
echo "Finished updating DB fields using current environment info :) "
