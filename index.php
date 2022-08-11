<?php

require_once("helper.php");
echo getHeader();
echo "<div> Fresh Installation ? <a target=blank href='install.php'> Yes</a> or <a target=blank href='install.php?flush'>No (Flush existing data)</a></div><br/>
            <div><a href='admin/inventory.php'>Manage Inventory</a> <r/><a href='order.php'>Place order</a> <t></div>";
echo getFooter();