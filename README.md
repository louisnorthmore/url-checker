# url-checker

###Checks URL's are correctly redirected to their destination.  

We needed a solution for bulk checking a load of 301/302 redriects.  
urls.csv should be a comma separated file consisting of two values: `<source_url>,<destination_url>`  

A summary is returned telling you how many are successful.

## Running  
`php -f urlchecker.php`

##Output  
`Summary: Total URLS: 204 Working: 64 Broken: 138 Errors: 2`  

Please raise any issues you find.


