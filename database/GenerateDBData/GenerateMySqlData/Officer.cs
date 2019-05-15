using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.InteropServices;
using MySql.Data.MySqlClient;

namespace GenerateMySqlData
{
    public class Officer
    {
        public int Id { get; set; }
        public int UserId { get; set; }
        public int ClubId { get; set; }
        public string Position{ get; set; }

        public Officer(int id, int userId, int clubId, string position)
        {
            Id = id;
            UserId = userId;
            ClubId = clubId;
            Position = position;
        }


        public static List<Officer> GenerateOfficers(MySqlConnection connection, Club club, List<User> users,
            HashSet<User> allOfficers)
        {

            var presidentUser = users.First(u => !allOfficers.Contains(u));
            Database.WriteOfficerToDatabase(connection, 
                new Officer(0, presidentUser.id, club.Id, "President"));
            allOfficers.Add(presidentUser);
            
            var vicePresidentUser = users.First(u => !allOfficers.Contains(u));
            Database.WriteOfficerToDatabase(connection, 
                new Officer(0, vicePresidentUser.id, club.Id, "Vice President"));
            allOfficers.Add(vicePresidentUser);
            
            var treasurerUser = users.First(u => !allOfficers.Contains(u));
            Database.WriteOfficerToDatabase(connection, 
                new Officer(0, treasurerUser.id, club.Id, "Treasurer"));
            allOfficers.Add(treasurerUser);
            
            var secretaryUser = users.First(u => !allOfficers.Contains(u));
            Database.WriteOfficerToDatabase(connection, 
                new Officer(0, secretaryUser.id, club.Id, "Secretary"));
            allOfficers.Add(secretaryUser);

            
            
            return Database.GetOfficersFromDatabase(connection).FindAll(o => o.ClubId == club.Id);
        }
    }
}