<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title> Victim </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <style>

     /* General styles */

body {
  font-family: Arial, sans-serif;
  font-size: 14px;
  line-height: 1.5;
  color: #333;
}

.resume {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ddd;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  background-color: #fff;
}

h1, h2, h3, h4, h5, h6 {
  margin-top: 0;
  margin-bottom: 10px;
}

ul {
  margin: 0;
  padding: 0;
  list-style: none;
}

.hidden {
  display: none;
}

/* Header section */

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.name {
  font-size: 24px;
  font-weight: bold;
}

.contact {
  font-size: 14px;
}

.email:before {
  content: "✉️ ";
}

.phone:before {
  content: "📞 ";
}

.address:before {
  content: "🏠 ";
}

/* Education and Experience sections */

.item {
  margin-bottom: 20px;
}

.title {
  font-size: 18px;
  font-weight: bold;
}

.subtitle {
  font-size: 14px;
  font-style: italic;
}

.date {
  font-size: 14px;
  font-style: italic;
  margin-bottom: 10px;
}

.description li {
  margin-bottom: 5px;
}

.show-certificate {
  background-color: #337ab7;
  color: #fff;
  border: none;
  padding: 5px 10px;
  border-radius: 3px;
  cursor: pointer;
  margin-top: 10px;
}

.certificate {
  margin-top: 10px;
}

/* Certificate popup */

.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.5);
}

.popup-content {
  max-width: 80%;
  max-height: 80%;
  overflow: auto;
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.popup-close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 24px;
  font-weight: bold;
  color: #333;
  cursor: pointer;
}
</style>
</head>
<body>
 <div class="resume">
  <div class="header">
    <div class="name">John Doe</div>
    <div class="contact">
      <div class="email">johndoe@email.com</div>
      <div class="phone">123-456-7890</div>
      <div class="address">1234 Main Street, Anytown, USA</div>
    </div>
  </div>

  <div class="summary">
    <h2>Summary</h2>
    <p>A highly motivated and detail-oriented individual with over 5 years of experience in software development.</p>
  </div>

  <div class="education">
    <h2>Education</h2>
    <div class="item">
      <div class="title">Master of Computer Science</div>
      <div class="subtitle">University of XYZ, Anytown, USA</div>
      <div class="date">Graduated: May 2020</div>
      <div class="description">
        <ul>
          <li>Coursework included algorithms, data structures, and database systems.</li>
          <li>Graduate research assistant for the Computer Science department.</li>
        </ul>
      </div>
     <button class="show-certificate" onclick="window.location.href='test4.php'">Show Certificate</button>
   <button class="show-certificate" onclick="window.location.href='test3.php'">Show Achievement</button>
      <div class="certificate hidden">
        <img src="path-to-certificate-image">
      </div>
    </div>

    <div class="item">
      <div class="title">Bachelor of Science in Computer Science</div>
      <div class="subtitle">University of ABC, Anytown, USA</div>
      <div class="date">Graduated: May 2017</div>
      <div class="description">
        <ul>
          <li>Coursework included programming languages, software engineering, and computer architecture.</li>
          <li>Recipient of the Computer Science department scholarship for outstanding academic achievement.</li>
        </ul>
      </div>
     <button class="show-certificate" onclick="window.location.href='test4.php'">Show Certificate</button>
      <button class="show-certificate" onclick="window.location.href='test3.php'">Show Achievement</button>
      <div class="certificate hidden">
        <img src="path-to-certificate-image">
      </div>
    </div>
  </div>

  <div class="experience">
    <h2>Experience</h2>
    <div class="item">
      <div class="title">Software Developer</div>
      <div class="subtitle">ABC Company, Anytown, USA</div>
      <div class="date">May 2020 - Present</div>
      <div class="description">
        <ul>
          <li>Developed and maintained software applications using Java and Python.</li>
          <li>Implemented RESTful APIs and web services using Spring Boot and Flask frameworks.</li>
          <li>Collaborated with cross-functional teams to deliver high-quality software products.</li>
        </ul>
      </div>
          <button class="show-certificate" onclick="window.location.href='test4.php'">Show Certificate</button>
      <button class="show-certificate" onclick="window.location.href='test3.php'">Show Achievement</button>
      <div class="certificate hidden">
        <img src="path-to-certificate-image">
      </div>
    </div>

    <div class="item">
      <div class="title">Software Engineer Intern</div>
      <div class="subtitle">XYZ Company, Anytown, USA</div>
      <div class="date">May 2018 - August 2018</div>
      <div class="description">
        <ul>
          <li>Assisted in the development and testing of software applications using C++ and Python.</li>
          <li>Participated in code reviews




</body>


</html>