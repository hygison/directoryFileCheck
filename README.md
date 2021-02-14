# directoryFileCheck
Check for new files on the directory - Suggested used along with a Cron Job

This script have the goal to keep track of the files in your system. There are some ways to upload files to your server that do not store the date or when the file was added or by whom. 
Also someone could inject a file in your server as well by using script and we would not want that. Or if something like that happen would be possible to know where is the added file consequently the admin would be able to solve the problem.
If there are a lot of files in your server, I suggest you run a cron job along with the DirectoryFile class to find some threats
