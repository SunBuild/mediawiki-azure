# MediaWiki 
With the button below, you can easily deploy the MediaWiki to Azure.  

[![Deploy to Azure](http://azuredeploy.net/deploybutton.png)](https://azuredeploy.net/)

## What Is MediaWiki
MediaWiki is a free and open-source wiki software package written in PHP.   
It serves as the platform for Wikipedia and the other projects of the Wikimedia Foundation,
which deliver content in over 280 languages to more than half a billion people each month.   
MediaWiki's reliability and robust feature set have earned it a large and vibrant community of third-party users and developers.  

## Features
* feature-rich and extensible, both on-wiki and with hundreds of extensions;  
* scalable and suitable for both small and large sites;  
* simple to install, working on most hardware/software combinations;  
* available in your language.  

## References Files
* System requirements     **RELEASE-NOTES**
* Installation            **INSTALL**
* Upgrade details         **UPGRADE**

## Links
* Ready to get started?                                 **https://www.mediawiki.org/wiki/Download**
* Looking for the technical manual?                     **https://www.mediawiki.org/wiki/Manual:Contents**
* Seeking help from a person?                           **https://www.mediawiki.org/wiki/Communication**
* Looking to file a bug report or a feature request?    **https://bugs.mediawiki.org/**
* Interested in helping out?                            **https://www.mediawiki.org/wiki/How_to_contribute**

## Manage MediaWiki On Azure 
* Use any FTP tool you prefer to connect to the site (you can get the credentials on Azure portal);  
* Customize **LocalSettings.php** generated from installation;  
* Upload **LocalSettings.php** to folder **/home/site/wwwroot**;  
```
        ## For Example: Modified the URL path to the logo. 
        ## $wgLogo = "$wgResourceBasePath/resources/assets/wiki.png"; 
        $wgLogo = "$wgResourceBasePath/resources/assets/mediawiki.png";
```

## CREDITS && COPYING
MediaWiki is the result of global collaboration and cooperation.   
The **CREDITS** file lists technical contributors to the project.   
The **COPYING** file explains MediaWiki's copyright and license (GNU General Public License, version 2 or later).   
Many thanks to the Wikimedia community for testing and suggestions.  