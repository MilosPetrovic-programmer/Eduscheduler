import React, { useState, useEffect } from "react";
import "./RightSideBar.css";
import axios from "axios";

const RightSideBar = () => {
  const [classrooms, setClassrooms] = useState([]);

  useEffect(() => {
    const fetchClassrooms = async () => {
      try {
        const response = await axios.get(
          "http://localhost/EduScheduler/api/check_classrooms.php"
        );
        setClassrooms(response.data);
        console.log(response.data);
      } catch (error) {
        console.error("Error fetching classrooms:", error);
      }
    };
    fetchClassrooms();
    setInterval(fetchClassrooms, 60000);
  }, []);

  return (
    <div className="rightSideBar">
      <h4 className="">Trenutno zauzete prostorije</h4>
      <ul>
        {classrooms.map((classroom) => (
          <li>
            {classroom.amphitheater}: {classroom.startTime}-{classroom.endTime}h
            ({classroom.professor})
          </li>
        ))}
      </ul>
    </div>
  );
};

export default RightSideBar;
