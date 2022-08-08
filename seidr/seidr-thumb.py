#!/usr/bin/python
#Seidr Thumbnailer - v0.1
import time
import os

while True:
    #Take snapshot of all running VMs
    os.system('/opt/seidr/seidr-thumb.sh')
    #Wait for 60 seconds
    time.sleep(60)
