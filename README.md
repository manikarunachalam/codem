# codem_interview
This project runs as a e-commerce setup with minimal features.

Requirements
------------
1. PHP7 (PEAR)
2. Mysql
3. JQuery

Installation
------------
Project setup can be run from the homepage by clicking the Installation link provided.

Mysql Connection
----------------
Project requires mysql configuration which needs to be set in the config.php

Inventory Setup
---------------
Inventory by default can be installed along with the installation setup,sample.csv file contains the format which needs to be as per the sample.
each row should be productname=quantity without headers forex:A=5 (A is the product name, 5 is the product quantity available)
This sample.csvfile can be modified and populate the inventory via the install.php or admin/inventory.php
