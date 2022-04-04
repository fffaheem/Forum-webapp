# Forum Web-app
## Project

![ForTheBadge built-by-developers](https://forthebadge.com/images/badges/built-by-developers.svg)       ![ForTheBadge built-by-developers](https://forthebadge.com/images/badges/for-you.svg)

LIVE HOSTED HERE-[AMU-Forum WEBSITE](http://amuforum.epizy.com/)

## Features
- Sign-up and log-in
- Ask Question
- Leave an answer 
- Leave reply
- Forgot password
- Email verification
- Searching and sorting
- liking and reporting
- Profile Management <br>
-- Edit and delete queries <br> 
-- Edit description <br>
-- Upload DP <br>
-- Change password <br> 
-- Delete account <br>
- Admin Panel <br> 
-- Edit and delete queries <br>
-- Queries sorting <br>
-- Report-count wise sorting  <br> 
-- Delete users <br> 
-- Manage Users <br> 
-- Manage admins <br> 
-- Send broadcast or particular notification <br>

## How to run locally?
- Clone the repository and paste it in the htdocs or www folder.
- Run Apache and mySql services or just run XAMPP or Laragon.
- Make database with the name of "forumWebsite" or change the database name in the partials/conn.php under the variable "database" [1]
- Find php.sql.
- import the php.sql in the database using phpmyadmin.
- Now inside this folder go to partials/mail.php and change your email id and password under "yourEmail@gmail.com" and "yourEmailPassword". <br>
Note:- Your email need to be less secured for mail services. [2]
- Inside the folder go to partials/linkAndMeta.php and under the "linkUrl" variable change the folder name to your folder name. [3]
- Inside the folder go to forget/indexBack.php and under the "website" variable change the folder name to your folder name. [4]
- Inside the folder go to partials/notificationBack.php and under the "website" variable change the folder name to your folder name. [5]
- Inside the folder go to partials/notificationChecker.js and under the "url" variable change the folder name to your folder name, in the place of "forumWebsite". Similarly do it for sessionChecker.js [6]
- Inside the folder go to partials/navbar.php and under the "website" variable change the folder name to your folder name. [7]
- Similarly for partials/miniNavbar.php and partials/miniUserNavbar.php.
- Inside the folder go to partials/adminNavbar.php and under the "website" variable change the folder name to your folder name i.e  "your-folder-name/admin". [8]
- Now you're good to go.
- On you web browser go to htttp://localhost/your-folder-name <br>
Make sure Apache and mySql services are running.
- Enjoy



<p align="center">
    [1] <br>
    <img src="./readmeImages/conn.png" width="400"/>
</p>


<p align="center">
    [2] <br>
    <img src="./readmeImages/mail.png" width="400" />
</p>



<p align="center">
    [3] <br>
    <img src="./readmeImages/link.png" width="400" />
</p>


<p align="center">
    [4] <br>
    <img src="./readmeImages/forget.png" width="400" />
</p>



<p align="center">
    [5] <br>
    <img src="./readmeImages/notification.png" width="400" />
</p>



<p align="center">
    [6] <br>
    <img src="./readmeImages/notification and session.png" width="700" />
</p>



<p align="center">
    [7] <br>
    <img src="./readmeImages/navbar.png" width="400" />
</p>


<p align="center">
    [8] <br>
    <img src="./readmeImages/adminNav.png" width="400" />
</p>

## Website setup
- Create your Id by signing up, make sure to enter right email id because email verification is needed to activate your account
- Go to admin panel by Clicking on the "admin" button which will appear on pressing the first tab on the website or by simply typing in "localhost/your-folder-name/admin".
- The Username is "test" and the password is "adminadmin" .
- After logging in go to "Manage Users" and assign yourself as admin.
- After assigning yourself as admin go to "Manage Admins" and assign yourself as SuperAdmin and then delete this test id.
- Enjoy.

## Website Screenshot

<p align="center">
  <img src="./readmeImages/1.png" />
</p>


<p align="center">
  <img src="./readmeImages/2.png" />
</p>


<p align="center">
  <img src="./readmeImages/3.png" />
</p>


<p align="center">
  <img src="./readmeImages/4.png" />
</p>


<p align="center">
  <img src="./readmeImages/5.png" />
</p>


<p align="center">
  <img src="./readmeImages/6.png" />
</p>


<p align="center">
  <img src="./readmeImages/7.png" />
</p>


<p align="center">
  <img src="./readmeImages/8.png" />
</p>


<p align="center">
  <img src="./readmeImages/9.png" />
</p>


<p align="center">
  <img src="./readmeImages/10.png" />
</p>


<p align="center">
  <img src="./readmeImages/11.png" />
</p>


<p align="center">
  <img src="./readmeImages/12.png" />
</p>


<p align="center">
  <img src="./readmeImages/13.png" />
</p>


<p align="center">
  <img src="./readmeImages/14.png" />
</p>


<p align="center">
  <img src="./readmeImages/15.png" />
</p>


<p align="center">
  <img src="./readmeImages/16.png" />
</p>


<p align="center">
  <img src="./readmeImages/17.png" />
</p>


<p align="center">
  <img src="./readmeImages/18.png" />
</p>


<p align="center">
  <img src="./readmeImages/19.png" />
</p>


<p align="center">
  <img src="./readmeImages/20.png" />
</p>


<p align="center">
  <img src="./readmeImages/21.png" />
</p>


<p align="center">
  <img src="./readmeImages/22.png" />
</p>


<p align="center">
  <img src="./readmeImages/23.png" />
</p>


<p align="center">
  <img src="./readmeImages/24.png" />
</p>


<p align="center">
  <img src="./readmeImages/25.png" />
</p>


<p align="center">
  <img src="./readmeImages/26.png" />
</p>


<p align="center">
  <img src="./readmeImages/27.png" />
</p>


<p align="center">
  <img src="./readmeImages/28.png" />
</p>


<p align="center">
  <img src="./readmeImages/29.png" />
</p>


<p align="center">
  <img src="./readmeImages/30.png" />
</p>


<p align="center">
  <img src="./readmeImages/31.png" />
</p>


<p align="center">
  <img src="./readmeImages/32.png" />
</p>


<p align="center">
  <img src="./readmeImages/33.png" />
</p>



### Let's Connect :coffee:
<p align="center">
	<a href="https://github.com/fffaheem" targer="_blank"><img src="https://img.icons8.com/bubbles/50/000000/github.png" alt="GitHub"/></a>
	<a href="https://www.linkedin.com/in/fffaheem/" targer="_blank" ><img src="https://img.icons8.com/bubbles/50/000000/linkedin.png" alt="LinkedIn"/></a>
	<a href="https://www.instagram.com/_ffaheem/" targer="_blank" ><img src="https://img.icons8.com/bubbles/50/000000/instagram.png" alt="Instagram"/></a>
	<a href="https://twitter.com/_ffaheem" targer="_blank"><img src="https://img.icons8.com/bubbles/50/000000/twitter.png" alt="Twitter"/></a>
  </p>



### Website Developed by
Mohd Faheem Ahmad <br>
 📫 Reach me: **mohdfaheemahmad5@gmail.com**



