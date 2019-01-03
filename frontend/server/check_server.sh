count=`ps -fe | grep -v "grep" | grep "zhibo_service" | wc -l`
flashcount=`ps -fe | grep -v "grep" | grep "zhibo_flashservice" | wc -l`
###checkzhibo_service###
if [ $count -lt 1 ];
then
ps -eaf | grep "socketserver.php" | grep -v "grep"| awk '{print $2}'|xargs kill -9
sleep 2
ulimit -c unlimited
/usr/local/php/bin/php /usr/local/apache2/htdocs/zhibo/frontend/server/socketserver.php
echo "start zhibo_service";
echo $(date +%Y-%m-%d_%H:%M:%S) >/usr/local/apache2/htdocs/zhibo/frontend/runtime/logs/socketserver.log
else
echo "the zhibo_service is running..."
fi
#####check_zhibo_flashservice######
if [ $flashcount -lt 1 ];
then
ps -eaf | grep "flash_policy.php" | grep -v "grep"| awk '{print $2}'|xargs kill -9
sleep 2
ulimit -c unlimited
/usr/local/php/bin/php /usr/local/apache2/htdocs/zhibo/frontend/server/flash_policy.php
echo "start zhibo_flashservice";
echo $(date +%Y-%m-%d_%H:%M:%S) >/usr/local/apache2/htdocs/zhibo/frontend/runtime/logs/socketserver.log
else
echo "the zhibo_flashservice is running..."
fi
