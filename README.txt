Project Members: Linh T Phan, Prabesh Bista, Spencer Hagan

Site Link:
	http://cs.gettysburg.edu/~hagasp01/cs360/Car-Pooling-On-Campus/

User Accounts for Testing:
	Admin Account: 7 or 1
	Regular Account: 3 or 5

What's Completed:
	- landing page
	- navbar that displays based off logged-in or not, admin or not
	- dashboard once logged in
		- Can see upcoming and past rides
			- visual distinction between driver or passenger
		- Search rides by location and date-range
		- Suggested upcoming rides to join
		- More info page on each ride
	- Users can create and join rides
	- Profile page for users
		- displays rates, reviews, etc.
	- Users can edit their info
	- admin page
		- can view profile of any user
		- can ban and unban users, visual distinction if they are or not

What's Incomplete:
	- Login is still just by uid
	- Missing admin ability to delete or hide inappropriate reviews

What Isn't Done At All (From Proposal "transportation (uber on campus)"):
	- None?

Functionalities Completed By Each Member:

	Linh T Phan:
	- Landing page
	- Admin ability to ban and unban users
		- Success and error messages if ban/unban worked
		- Ban deletes all future rides that user created
		- Removes them as passenger from any future rides
		- Deletes all ratings made by user
		- All user's cars, payment info
	-Ride search, filter, & result
		- Combined filtering form for destination (most popular 4), date range(from, to)
		- Clickable destination list with toggling highlight (blue on click, deselect with second click)
		- Filters only trigger search when Search button is clicked
		- Supports any combination of filters:
			- Destination only
			- from-date only
			- to-date only
			- all or none
		- If no filter selected, shows all rides starting from today
		- Excludes banned user's own rides from search results
	- Ride Search page
	
	Prabesh Bista:
	- dashboard
		- shows upcoming and past rides, upcoming suggested rides
		- visual distinction betweeen driver and passenger rides
		- search bar on top to search for rides by destination
	- view details: cancel ride/booking, driver and ride info
		- ability to review users once a ride has completed
		- if driver, shows info about passengers who booked
			- driver can cancel ride from here
		- if passenger, shows info about the driver
	- navbar basic functionality and display

	Spencer Hagan:
	- Form to create a new ride
		- what car
		- how many free seats
		- destination and time
		- range for requested price (gas, transportation, etc.)
	- Profile page for users, displaying:
		- cars
		- rides
		- ratings
		- reviews
		- contact info
	- Form to edit user info, including:
		- Contact info
		- Add and delete payment types
		- Add and delete cars
	- Admin access to any user's profile page
	- navbar changes based on status (logged-in, admin)
