#!/usr/bin/python
# -*- coding: utf-8 -*-

import json
import urllib2
secret ='secret'
baseurl='http://pooltable.jasongi.com/'
url = baseurl + 'gettrans.php?secret=' + secret
req = urllib2.Request(url)
f = urllib2.urlopen(req)
response = f.read()
f.close()
print response
if response != 'error' and response != 'false' and response != False:
    obj = json.loads(response)
    trans = str(obj['transaction_hash'])
    url = baseurl + 'deltrans.php?secret=' + secret + '&trans=' + trans #move to process transactions table
    req = urllib2.Request(url)
    f = urllib2.urlopen(req)
    response = f.read()
    f.close()
    if response == '*ok*':
        print response
        #run solanoid script
