using System;
using System.Collections.Generic;
using System.Linq;
using MySql.Data.MySqlClient;

namespace GenerateMySqlData
{
    public static class Database
    {
        public static MySqlConnection GenerateConnection()
        {
            var server = "162.250.28.130";
            var port = "36000";
            var database = "CS461";
            var userName = "cs461";
            var pw= "461Club";
            var connectionString =
                $"Server={server};Port={port};Database={database};Uid={userName};Pwd={pw};";
            
            var con =  new MySqlConnection(connectionString);
            con.Open();
            return con;
        }
        
        public static void WriteUserToDatabase(MySqlConnection connection, User user)
        {
            var sqlWrite = @"
                INSERT INTO User (name, student_id, email, phone, password)
                VALUES (@name, @studentId, @email, @phone, SHA2(@pw, 512))
            ";

            var command = connection.CreateCommand();
            command.CommandText = sqlWrite;
            command.Parameters.Add(new MySqlParameter("@name", user.name));
            command.Parameters.Add(new MySqlParameter("@studentId", user.studentId));
            command.Parameters.Add(new MySqlParameter("@email", user.email));
            command.Parameters.Add(new MySqlParameter("@phone", user.phone));
            command.Parameters.Add(new MySqlParameter("@pw", user.password));

            var transaction = connection.BeginTransaction();
            try
            {
                command.ExecuteNonQuery();
                transaction.Commit();
                transaction.Dispose();
            }
            catch (MySqlException ex)
            {
                Console.WriteLine(ex);
                transaction.Rollback();
                transaction.Dispose();
            }
            
        }

        public static List<User> GetUsersFromDatabase(MySqlConnection connection)
        {
            var sqlRead = @"
                SELECT id, name, student_id, email, phone, password 
                FROM User
            ";
            
            var command = connection.CreateCommand();
            command.CommandText = sqlRead;

            var reader = command.ExecuteReader();
            var users = new List<User>();

            while (reader.Read())
            {
                var id = reader.GetInt32("id");
                var name = reader.GetString("name");
                var studentId = reader.GetString("student_id");
                var email = reader.GetString("email");
                var phone = reader.IsDBNull(4) ? "" : reader.GetString("phone");
                var password = reader.GetString("password");

                users.Add(new User(id, name, studentId, email, phone, password));
            }
            reader.Close();
            reader.Dispose();
            return users;
        }
        
        
        public static void WriteClubToDatabase(MySqlConnection connection, Club club)
        {
            var sqlWrite = @"
                INSERT INTO Club (name, president, section, description, faculty_advisor, 
                  faculty_email, norm_meeting_days, norm_meeting_time, norm_meeting_loc, 
                  picture) 
                VALUES (@name, @president, @section, @description, @facultyName, 
                        @facultyEmail, @normalMeetDays, @normalMeetTime, @normalMeetLoc, 
                        @picture)
            ";

            var command = connection.CreateCommand();
            command.CommandText = sqlWrite;
            command.Parameters.Add(new MySqlParameter("@name", club.Name));
            command.Parameters.Add(new MySqlParameter("@president", club.President));
            command.Parameters.Add(new MySqlParameter("@section", club.Section));
            command.Parameters.Add(new MySqlParameter("@description", club.Description));
            command.Parameters.Add(new MySqlParameter("@facultyName", club.FacultyAdviser));
            command.Parameters.Add(new MySqlParameter("@facultyEmail", club.FacultyEmail));
            command.Parameters.Add(new MySqlParameter("@normalMeetDays", club.MeetingDay));
            command.Parameters.Add(new MySqlParameter("@normalMeetTime", club.MeetingTime));
            command.Parameters.Add(new MySqlParameter("@normalMeetLoc", club.MeetingLocation));
            command.Parameters.Add(new MySqlParameter("@picture", club.Picture));
            

            var transaction = connection.BeginTransaction();
            try
            {
                command.ExecuteNonQuery();
                transaction.Commit();
                transaction.Dispose();
            }
            catch (MySqlException ex)
            {
                Console.WriteLine(ex);
                transaction.Rollback();
                transaction.Dispose();
            }
            
        }
        
        public static List<Club> GetClubsFromDatabase(MySqlConnection connection)
        {
            var sqlRead = @"
                SELECT id, name, president, section, description, faculty_advisor, 
                  faculty_email, norm_meeting_days, norm_meeting_time, norm_meeting_loc, 
                  picture
                FROM Club
            ";
            
            var command = connection.CreateCommand();
            command.CommandText = sqlRead;

            var reader = command.ExecuteReader();
            var clubs = new List<Club>();

            while (reader.Read())
            {
                var id = reader.GetInt32("id");
                var name = reader.GetString("name");
                var president = reader.GetInt32("president");
                var section = reader.GetString("section");
                var description = reader.GetString("description");
                var facultyAdvisor = reader.GetString("faculty_advisor");
                var facultyEmail = reader.GetString("faculty_email");
                var meetingDays = reader.GetString("norm_meeting_days");
                var meetingTime = reader.GetString("norm_meeting_time");
                var meetingLoc = reader.GetString("norm_meeting_loc");
                var picture = reader.IsDBNull(10) ? "" : reader.GetString("picture");

                
                clubs.Add(new Club(id, name, section, description, facultyAdvisor, facultyEmail,
                meetingDays, meetingTime, meetingLoc, picture, president:president));
            }

            reader.Close();
            reader.Dispose();
            return clubs;
        }


        public static void UpdateClubPresident(MySqlConnection connection, Club club, Officer president)
        {
            var sqlWrite = @"
                UPDATE Club 
                SET president = @president
                WHERE id = @club_id
            ";

            var command = connection.CreateCommand();
            command.CommandText = sqlWrite;
            command.Parameters.Add(new MySqlParameter("@president", president.UserId));
            command.Parameters.Add(new MySqlParameter("@club_id", club.Id));
            

            var transaction = connection.BeginTransaction();
            try
            {
                command.ExecuteNonQuery();
                transaction.Commit();
                transaction.Dispose();
            }
            catch (MySqlException ex)
            {
                Console.WriteLine(ex);
                transaction.Rollback();
                transaction.Dispose();
            }
        }
        
        
        public static void WriteOfficerToDatabase(MySqlConnection connection, Officer officer)
        {
            var sqlWrite = @"
                INSERT INTO Officers (user_id, club_id, position)
                VALUES (@user_id, @club_id, @position)
            ";

            var command = connection.CreateCommand();
            command.CommandText = sqlWrite;
            command.Parameters.Add(new MySqlParameter("@user_id", officer.UserId));
            command.Parameters.Add(new MySqlParameter("@club_id", officer.ClubId));
            command.Parameters.Add(new MySqlParameter("@position", officer.Position));

            var transaction = connection.BeginTransaction();
            try
            {
                command.ExecuteNonQuery();
                transaction.Commit();
                transaction.Dispose();
            }
            catch (MySqlException ex)
            {
                Console.WriteLine(ex);
                transaction.Rollback();
                transaction.Dispose();
            }
            
        }

        public static List<Officer> GetOfficersFromDatabase(MySqlConnection connection)
        {
            var sqlRead = @"
                SELECT id, user_id, club_id, position
                FROM Officers
            ";
            
            var command = connection.CreateCommand();
            command.CommandText = sqlRead;

            var reader = command.ExecuteReader();
            var officers = new List<Officer>();

            while (reader.Read())
            {
                var id = reader.GetInt32("id");
                var userId = reader.GetInt32("user_id");
                var clubId = reader.GetInt32("club_id");
                var position = reader.GetString("position");

                officers.Add(new Officer(id, userId,clubId, position));
            }

            reader.Close();
            reader.Dispose();
            return officers;
        }
        
        
        public static void WriteMeetingToDatabase(MySqlConnection connection, Meeting meeting)
        {
            var sqlWrite = @"
                INSERT INTO Meeting (club_id, event_date, location, meeting_type) 
                VALUES (@club_id, @event_date, @location, @meeting_type)
            ";

            var command = connection.CreateCommand();
            command.CommandText = sqlWrite;
            command.Parameters.Add(new MySqlParameter("@club_id", meeting.Club.Id));
            command.Parameters.Add(new MySqlParameter("@event_date", meeting.MeetingTime));
            command.Parameters.Add(new MySqlParameter("@location", meeting.Location));
            command.Parameters.Add(new MySqlParameter("@meeting_type", meeting.MeetingType));
            

            var transaction = connection.BeginTransaction();
            try
            {
                command.ExecuteNonQuery();
                transaction.Commit();
                transaction.Dispose();
            }
            catch (MySqlException ex)
            {
                Console.WriteLine(ex);
                transaction.Rollback();
                transaction.Dispose();
            }
            
        }
        
        public static List<Meeting> GetMeetingsFromDatabase(MySqlConnection connection, List<Club> clubs)
        {
            var sqlRead = @"
                SELECT id, club_id, event_date, location, meeting_type
                FROM Meeting
            ";
            
            var command = connection.CreateCommand();
            command.CommandText = sqlRead;

            var reader = command.ExecuteReader();
            var meetings = new List<Meeting>();

            while (reader.Read())
            {
                var id = reader.GetInt32("id");
                var clubId = reader.GetInt32("club_id");
                var eventTime = reader.GetDateTime("event_date");
                var location = reader.GetString("location");
                var meetingType = reader.GetString("meeting_type");

                
                meetings.Add(new Meeting(id, clubs.First(c=> c.Id == clubId), eventTime, location, meetingType));
            }

            reader.Close();
            reader.Dispose();
            return meetings;
        }
        
        
        public static void WriteAttendanceToDatabase(MySqlConnection connection, Attendance attendance)
        {
            var sqlWrite = @"
                INSERT INTO Attendance (club_id, user_id, meeting, checked_in) 
                VALUES (@club_id, @user_id, @meeting, @checked_in)
            ";

            var command = connection.CreateCommand();
            command.CommandText = sqlWrite;
            command.Parameters.Add(new MySqlParameter("@club_id", attendance.Club.Id));
            command.Parameters.Add(new MySqlParameter("@user_id", attendance.User.id));
            command.Parameters.Add(new MySqlParameter("@meeting", attendance.Meeting.MeetingId));
            command.Parameters.Add(new MySqlParameter("@checked_in", attendance.CheckIn));
            

            var transaction = connection.BeginTransaction();
            try
            {
                command.ExecuteNonQuery();
                transaction.Commit();
                transaction.Dispose();
            }
            catch (MySqlException ex)
            {
                Console.WriteLine(ex);
                transaction.Rollback();
                transaction.Dispose();
            }
            
        }
        
        public static List<Attendance> GetAttendanceFromDatabase(MySqlConnection connection, 
            List<Club> clubs, List<User> users, List<Meeting> meetings)
        {
            var sqlRead = @"
                SELECT id, club_id, user_id, meeting, checked_in
                FROM Attendance
            ";
            
            var command = connection.CreateCommand();
            command.CommandText = sqlRead;

            var reader = command.ExecuteReader();
            var attendance = new List<Attendance>();

            while (reader.Read())
            {
                var id = reader.GetInt32("id");
                var clubId = reader.GetInt32("club_id");
                var userId = reader.GetInt32("user_id");
                var meetingId = reader.GetInt32("meeting");
                var checkIn = reader.GetDateTime("checked_in");

                
                attendance.Add(new Attendance(id, clubs.First(c=> c.Id == clubId),
                    users.First(u=> u.id == userId),meetings.First(m => m.MeetingId == meetingId),
                    checkIn));
            }

            reader.Close();
            reader.Dispose();
            return attendance;
        }
    }
}