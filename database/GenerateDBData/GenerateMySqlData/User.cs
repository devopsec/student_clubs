using System;
using System.Linq;
using RandomNameGeneratorLibrary;

namespace GenerateMySqlData
{
    public class User
    {
        public int id { get; set; }
        public string name { get; set; }
        public string studentId { get; set; }
        public string email { get; set; }
        public string phone { get; set; }
        public string password { get; set; }

        public User(int id, string name, string studentId, string email, string phone, string password)
        {
            this.id = id;
            this.name = name;
            this.studentId = studentId;
            this.email = email;
            this.phone = phone;
            this.password = password;
        }
        
        public static User GenerateUser(PersonNameGenerator nameGenerator, Random random)
        {
            string fName = nameGenerator.GenerateRandomFirstName();
            string lName = nameGenerator.GenerateRandomLastName();
            string studentId = $"70{random.Next(2000000, 9000000)}";
            var email = $"{lName.Substring(0, 4).ToLower()}{studentId.Substring(studentId.Length - 4, 4)}@kettering.edu";
            var phone = $"({random.Next(100, 999)}){random.Next(100, 999)}-{random.Next(1000, 9999)}";
            var pw = studentId;
            return new User(0, $"{fName} {lName}", studentId, email, phone, pw);
        }

        public override string ToString()
        {
            return $"id: {id}, name: {name}, studentId: {studentId}, email: {email}, phone: {phone}, password: {password}";
        }
    }
}