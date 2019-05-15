using System;
using System.Collections.Generic;
using RandomNameGeneratorLibrary;

namespace GenerateMySqlData
{
    class Program
    {
        static void Main(string[] args)
        {
            var random = new Random((int)DateTime.Now.Ticks);
            var nameGenerator = new PersonNameGenerator(random);
            var connection = Database.GenerateConnection();


            /* Generate Users
            
            var users = new List<User>();
            for (int i = 0; i < 30; i++)
            {
                var user = User.GenerateUser(nameGenerator, random);
                users.Add(user);
                Console.WriteLine(user);
                Database.WriteUserToDatabase(connection, user);
            }*/


            /* Generate clubs and officers
            var allOfficers = new HashSet<User>();
            foreach (var officer in Database.GetOfficersFromDatabase(connection))
            {
                allOfficers.Add(users.Find(u => u.id == officer.UserId));
            }
            
            var clubs = new List<Club>();
            for (int i = 0; i < 10; i++)
            {
                clubs.Add(Club.GenerateClub(connection, users, nameGenerator, random, allOfficers));
            }
            */
            
            
            var users = Database.GetUsersFromDatabase(connection);
            var clubs = Database.GetClubsFromDatabase(connection);
            var officers = Database.GetOfficersFromDatabase(connection);

            //var meetings = Meeting.GenerateMeetings(connection, clubs);
            var meetings = Database.GetMeetingsFromDatabase(connection, clubs);
            var attendences = Attendance.GenerateAttendances(connection, random, clubs, users, meetings, officers);
            
            connection.Close();
        }
        
        
        
    }
}