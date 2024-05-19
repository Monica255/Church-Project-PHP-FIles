# Steps

1. Clone this repository
2. Copy the whole file to XAMPP > htdocs (rename the filename to "church")
3. Go to XAMPP > etc > php.ini > check this line "date.timezone=Asia/Makassar" (line 900ish) > change to your region (example > "date.timezone=Asia/Jakarta" )
4. Run server
5. Open phpmyadmin and create a database named "church"
6. Create a table named users with attributes email(VARCHAR), name(VARCHAR), password(VARCHAR), role(VARCHAR) (email is the primary key and auto incremented)
7. Create a table named attendance with attributes	id(INT), timestamp(timestamp), id_event(VARCHAR), name_event(VARCHAR), email_user(CARCHAR) (id is the primary key and auto incremented)
8. Create a table named kegiatan with attributes id_event(INT), name_event(VARCHAR), start_time(VARCHAR), end_time(VARCHAR), date(VARCHAR) (id_event is the primary key and auto incremented)

NOTE: If you signup with @admin email, the account created will have the role 'admin' else 'jemaat'
