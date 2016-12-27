--- 
customlog: 
  - 
    format: combined
    target: /usr/local/apache/domlogs/kcttopspinclub.homeworksforbooks.com
  - 
    format: "\"%{%s}t %I .\\n%{%s}t %O .\""
    target: /usr/local/apache/domlogs/kcttopspinclub.homeworksforbooks.com-bytes_log
documentroot: /home/homewot5/public_html/kcttopspinclub
group: homewot5
hascgi: 1
homedir: /home/homewot5
ifmodulemodsuphpc: 
  group: homewot5
ip: 162.144.69.239
ipv6: ~
no_cache_update: 0
owner: root
phpopenbasedirprotect: 1
port: 80
scriptalias: 
  - 
    path: /home/homewot5/public_html/kcttopspinclub/cgi-bin/
    url: /cgi-bin/
serveradmin: webmaster@kcttopspinclub.homeworksforbooks.com
serveralias: www.kcttopspinclub.homeworksforbooks.com www.kcttopspinclub.com kcttopspinclub.com
servername: kcttopspinclub.homeworksforbooks.com
usecanonicalname: 'Off'
user: homewot5
userdirprotect: ''
