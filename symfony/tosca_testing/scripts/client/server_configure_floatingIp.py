#!/usr/bin/env python
import subprocess
import os
def main():
    proc = subprocess.Popen("screen -d -m -S server iperf -c $client_private_floatingIp -t 300".split(), stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    out1, err1 = proc.communicate()
    return out1

main()
