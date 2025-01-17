Error Codes Format

The format is as follow : xxxx

- Where did the error occur ? :

Org : 01

Recruiter : 02

Worker: 03

Scripts : 04

- In which controller ?

Auth: 01

App: 02

Opportunities: 03

Jobs: 04

Dashboard: 05

Plain : 06 (mostly for scripts i guess)

Feel free to reserve more digits here

- What type ?

Global catch : 01 (like the whole function is not working, mostly for unexpected)

Mail : 02 (An email didn't go through)

Functional : 03 ( a function screwd up, see the next X to add the correct position)

Dependency: 04 (Could be a vendor, a dependency or another piece of code imported, it would also send its own error code)

Feel free to add more

- Where in the controller ?

The function index, meaning that there would be no more function addition on top of the file.
If a function needs to be deleted, please adjust the indexes

example :

01010211 => Org Auth controller Mail error in post_login

01020113 => Org Application controller global catch in get_offer_information_for_edit (since it starts with try, this function has a global catch)

01030304 => Org Opportunities controller functional error in store

The idea is to provide the users with an error code that we could then use to test and try to replicate instead of full errors or undefined errors.
