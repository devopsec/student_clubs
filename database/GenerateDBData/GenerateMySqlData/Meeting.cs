using System;
using System.Collections.Generic;
using MySql.Data.MySqlClient;

namespace GenerateMySqlData
{
    public class Meeting
    {
        public int MeetingId { get; set; }
        public Club Club { get; set; }
        public DateTime MeetingTime { get; set; }
        public string Location { get; set; }
        public string MeetingType { get; set; }


        public Meeting(int meetingId, Club club, DateTime meetingTime, string location, string meetingType)
        {
            MeetingId = meetingId;
            Club = club;
            MeetingTime = meetingTime;
            Location = location;
            MeetingType = meetingType;
        }

        public static List<Meeting> GenerateMeetings(MySqlConnection connection, List<Club> clubs)
        {
            var currentDate = DateTime.Parse("03/31/2019");
            while (currentDate < DateTime.Parse("06/01/2019"))
            {
                foreach (var club in clubs)
                {
                    if (club.MeetingDay.ToLower().Contains(currentDate.DayOfWeek.ToString().ToLower().Substring(0, 3)))
                    {
                        var meetingLocation = club.MeetingLocation;
                        var meetingTime = DateTime.Parse( $"{currentDate.Year}/{currentDate.Month}/{currentDate.Day} {club.MeetingTime}");
                        var meetingType = club.MeetingTime.Equals("12:20:00") ? "Lunch" : "Activity";
                        Database.WriteMeetingToDatabase(connection, new Meeting(0, club, meetingTime, meetingLocation, meetingType));
                    }
                }

                currentDate = currentDate.AddDays(1);
            }

            return Database.GetMeetingsFromDatabase(connection, clubs);
        }
        
    }
}