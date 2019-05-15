using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using Google.Apis.Services;
using MySql.Data.MySqlClient;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using RandomNameGeneratorLibrary;

namespace GenerateMySqlData
{
    public class Club
    {
        public int Id { get; set; }
        public string Name { get; set; }
        public int President { get; set; }
        public string Section { get; set; }
        public string Description { get; set; }
        public string FacultyAdviser { get; set; }
        public string FacultyEmail { get; set; }
        public string MeetingDay { get; set; }
        public string MeetingTime { get; set; }
        public string MeetingLocation { get; set; }
        public string Picture { get; set; }

        public List<Officer> Officers { get; set; }

        public Club(int id, string name, string section, string description, 
            string facultyAdviser, string facultyEmail, string meetingDay, string meetingTime, 
            string meetingLocation, string picture, int president = -1)
        {
            Id = id;
            Name = name;
            President = president;
            Section = section;
            Description = description;
            FacultyAdviser = facultyAdviser;
            FacultyEmail = facultyEmail;
            MeetingDay = meetingDay;
            MeetingTime = meetingTime;
            MeetingLocation = meetingLocation;
            Picture = picture;
        }


        public static Club GenerateClub(MySqlConnection connection, List<User> users, 
            PersonNameGenerator nameGenerator, Random random,HashSet<User> allOfficers)
        {
            var name = GenerateName(random);
            var section = random.Next() % 2 == 0 ? "a" : "b";
            //var president = officers.First(off => off.Position.Equals("President")).UserId;
            var description = $"Meeting for those interested in being a member of Kettering's {name}";
            var facFName = nameGenerator.GenerateRandomFirstName();
            var facLName = nameGenerator.GenerateRandomLastName();
            var facName = $"{facFName} {facLName}";
            var facEmail = $"{facFName[0]}{facLName}@kettering.fake".ToLower();
            var meetingDay = GenerateMeetingDay(random);
            var meetingTime = GenerateMeetingTime(random);
            var meetingLocation = $"AB-{random.Next(1000, 4500)}";
            var imageLink = GenerateImageLink(random, name);
            
            
            
            //var club = new Club(name, section, description, facName, facEmail, meetingDay, meetingTime, meetingLocation, imageLink);
            Database.WriteClubToDatabase(connection, new Club(0, name, section, description, facName, facEmail,
                meetingDay, meetingTime, meetingLocation, imageLink) );
            var club = Database.GetClubsFromDatabase(connection).First(c => c.Name == name && c.Section == section);

            var officers = Officer.GenerateOfficers(connection, club, users, allOfficers );
            Database.UpdateClubPresident(connection, club, officers.First(o => o.Position.Equals("President")));
            //club.Officers = officers;

            return Database.GetClubsFromDatabase(connection).First(c => c.Id == club.Id);

        }

        private static string GenerateName(Random random)
        {
            var activityList = new List<string>
            {
                "Bowling", "Skiing", "Hiking", "Climbing", "Fishing", "Paintball", "Baseball",
                "Bird Watching", "Cloud Watching", "Disk Golf", "Soccer", "Anime", "Scuba", "Farming",
                "Gaming", "Board Games", "Martial Arts", "Hockey", "Football", "Investing"
            };
            var typeList = new List<string>
            {
                "Enthusiasts", "Engineers", "Society", "Association", "Group", "Club"
            };

            return $"{activityList[random.Next(0, activityList.Count)]} {typeList[random.Next(0, typeList.Count)]}";
        }

        private static List<Officer> GenerateOfficers(Random random, List<User> users, int clubId, int president)
        {
            var presidentUser = users.First(u => u.id == president);
            var vicePresidentUser = users[random.Next(0, users.Count)];
            var secretaryUser = users[random.Next(0, users.Count)];
            var treasurer = users[random.Next(0, users.Count)];

            return new List<Officer>
            {
                new Officer(presidentUser.id, presidentUser.id, clubId, "President"),
                new Officer(vicePresidentUser.id, vicePresidentUser.id, clubId, "Vice President"),
                new Officer(secretaryUser.id, secretaryUser.id, clubId, "Secretary"),
                new Officer(treasurer.id, treasurer.id, clubId, "Treasurer")
            };
        }
        
        private static string GenerateMeetingDay(Random random)
        {
            var dayList = new List<string>
            {
                "Mon", "Tue", "Wed", "Thur", "Fri"
            };

            return $"{dayList[random.Next(0, dayList.Count)]}";
        }
        private static string GenerateMeetingTime(Random random)
        {
            var timeList = new List<string>
            {
                "12:20:00", "18:00:00"
            };

            return $"{timeList[random.Next(0, timeList.Count)]}";
        }
        
        public static string GenerateImageLink(Random random, string activity)
        {
            var googleAPIKey = "AIzaSyB4lZf1A_Y2SHr-th70NFA1LO45Og7qnog";
            var engineKey = "009171846016145095041%3A_sk-aho9c3g";
            //var searchObject = new List<string>(){"volcano", "ocean", "beach"};
            //var searchTerm = searchObject[random.Next(0, searchObject.Count)];
            var searchType = "image";
            var index = random.Next(1, 50);

            var url = $"https://www.googleapis.com/customsearch/v1?" +
                         $"q={activity}" +
                         //$"q={searchTerm}" +
                         $"&imgSize=large" +
                         $"&cx={engineKey}" +
                         $"&num=1" +
                         //$"&alt=json" +
                         $"&searchType={searchType}" +
                         $"&start={index}" +
                         $"&key={googleAPIKey}";
            
            //return url;
            HttpWebRequest request = (HttpWebRequest)WebRequest.Create(url);
            request.AutomaticDecompression = DecompressionMethods.GZip;
            var html = "";
            using (HttpWebResponse response = (HttpWebResponse)request.GetResponse())
            using (Stream stream = response.GetResponseStream())
            using (StreamReader reader = new StreamReader(stream))
            {
                html = reader.ReadToEnd();
            }

            var json = (JObject)JsonConvert.DeserializeObject(html);
            var jarr = (JObject) json.GetValue("items")[0];
            return jarr.GetValue("link").ToString();
        }
        
        
    }
}