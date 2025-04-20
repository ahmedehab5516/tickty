# Movie Seat Booking System - Full Project Overview

Welcome to the **Movie Seat Booking System**! This system allows users to book seats for movie showtimes, with a smooth flow for selecting seats, confirming the booking, and proceeding with payment. Additionally, it offers several backend management features for movies, showtimes, and seat availability.

This README provides an overview of the entire system, covering both frontend and backend components, as well as how users and admins interact with the system.

## Features of the System

### **1. Movie & Showtime Management**

   - **Movie Listings**: The system allows admins to manage a list of movies, each with a title, description, and showtimes.
   - **Showtimes**: Users can view available showtimes for each movie.
   - **Movie Details**: When selecting a movie, users see all details about the movie, including available showtimes.

### **2. Seat Selection for Showtimes**
   
   - **Seat Layout**: For each movie's showtime, a seat map is displayed where users can see available, booked, and selected seats.
   - **Seat Selection**: Users can choose seats by clicking on available seats, which will highlight in the seat map.
   - **Dynamic Updates**: The seat map automatically updates to reflect the availability of seats, ensuring that users do not select already booked seats.

### **3. Booking Management**
   
   - **Confirm Seat Selection**: After selecting a seat, users can confirm their seat selection for a particular showtime.
   - **Booking History**: Users can view their previous bookings, including movie name, seat, and showtime.
   - **Booking Details**: The system records all details related to a booking, such as the selected seats, payment method, and movie information.

### **4. Payment Options**
   
   - **Payment Methods**: The system supports different payment methods for users to choose from, including:
     - **Cash**
     - **Credit Card**
     - **E-wallet**
   - **Payment Confirmation**: After confirming the seat selection, users can proceed to pay for their booking using their chosen payment method.
   - **Pricing Logic**: Pricing can be dynamic (based on the movie/showtime) or fixed for all movies.

### **5. Admin Features (Backend)**
   
   - **Admin Panel**: Admins can manage movies, showtimes, and seat availability.
   - **Movie Management**: Admins can add, update, or remove movies from the system.
   - **Showtime Management**: Admins can create new showtimes for each movie.
   - **Seat Management**: Admins can adjust the seat map layout (e.g., number of rows and columns) and mark seats as unavailable if necessary.

### **6. Dynamic Seat Map Interaction**
   
   - **Row and Column Dropdowns**: Users can select their preferred row and column using dropdown menus.
   - **Real-Time Seat Availability**: As users select seats, the seat availability dynamically updates on the page.
   - **Highlighting Selected Seat**: Users can click a seat or select it from a dropdown, which will highlight and update the booking information.

### **7. Local Storage for Seat Persistence**
   
   - **Session Persistence**: Selected seats are stored temporarily in the browser’s local storage, so users can view their selections even if they reload the page or navigate away.
   - **Local Storage for Booking Data**: The system saves the selected movie, seat, and payment method in the browser’s local storage until the user proceeds with payment.

## Tech Stack

The system is built using the following technologies:

- **Laravel**: A powerful PHP framework used for backend development.
- **PHP**: The server-side language powering the backend logic.
- **MySQL**: Database for storing movies, showtimes, seats, bookings, and user data.
- **Blade Templating Engine**: Used for rendering views on the frontend.
- **CSS (Bootstrap)**: For styling the UI and ensuring responsiveness.
- **JavaScript**: For interactive seat selection and updating UI elements without reloading the page.

## Database Models

### **1. Movie**
   - **Attributes**: Title, description, genre, duration, etc.
   - **Relationships**: Has many showtimes.
   
### **2. Showtime**
   - **Attributes**: Movie ID, start time, hall ID.
   - **Relationships**: Belongs to a movie, belongs to a hall, and has many bookings.

### **3. Seat**
   - **Attributes**: Hall ID, seat row, seat column, seat type.
   - **Relationships**: Belongs to a hall and has many bookings.

### **4. Booking**
   - **Attributes**: User ID, showtime ID, seat ID, payment method, booking status.
   - **Relationships**: Belongs to a user, showtime, and seat.

### **5. User**
   - **Attributes**: Name, email, password, etc.
   - **Relationships**: Has many bookings.

## Setup Instructions

### 1. Clone the repository:
```bash
git clone https://github.com/yourusername/movie-seat-booking.git
cd movie-seat-booking
