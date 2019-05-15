using System;
using System.Collections.Generic;
using System.Linq;
using MySql.Data.MySqlClient;

namespace GenerateMySqlData
{
    public class Attendance
    {
        public int AttendanceId { get; set; }
        public Club Club { get; set; }
        public User User { get; set; }
        public Meeting Meeting { get; set; }
        public DateTime CheckIn { get; set; }

        public Attendance(int attendanceId, Club club, User user, Meeting meeting, DateTime checkIn)
        {
            AttendanceId = attendanceId;
            Club = club;
            User = user;
            Meeting = meeting;
            CheckIn = checkIn;
        }

        public static List<Attendance> GenerateAttendances(MySqlConnection connection, Random random, List<Club> clubs, List<User> users, List<Meeting> meetings, List<Officer> officers)
        {
            foreach (var meeting in meetings)
            {
                foreach (var user in users)
                {
                    var number = random.Next() % 100;
                    if ( officers.Any(o => o.ClubId == meeting.Club.Id && o.UserId == user.id) && number < 85)
                    {
                        Database.WriteAttendanceToDatabase(connection, 
                            new Attendance(0, meeting.Club, user, meeting, meeting.MeetingTime.AddMinutes(random.Next(-10, 15))));
                    }
                    else if (number < 10)
                    {
                        Database.WriteAttendanceToDatabase(connection, 
                            new Attendance(0, meeting.Club, user, meeting, meeting.MeetingTime.AddMinutes(random.Next(-10, 15))));
                    }
                }
            }

            return Database.GetAttendanceFromDatabase(connection, clubs, users, meetings);
        }
    }
}