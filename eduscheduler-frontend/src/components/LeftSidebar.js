import React, { useState, useEffect } from "react";
import axios from "axios";
import "./LeftSidebar.css";

const LeftSidebar = ({ isLoggedIn }) => {
  const [scheduledAppointments, setScheduledAppointments] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (isLoggedIn) {
      const fetchScheduledAppointments = async () => {
        try {
          const response = await axios.get(
            "http://localhost/EduScheduler/api/scheduled_appointments.php",
            { withCredentials: true }
          );
          if (response.data.success !== false) {
            setScheduledAppointments(response.data);
          }
        } catch (error) {
          console.error("Error fetching scheduled appointments:", error);
        } finally {
          setLoading(false);
        }
      };

      fetchScheduledAppointments();
      const interval = setInterval(fetchScheduledAppointments, 10000);

      return () => clearInterval(interval); // cleanup interval
    } else {
      setLoading(false);
    }
  }, [isLoggedIn]);

  const handleDeleteAppointment = async (appointmentId) => {
    const confirmDelete = window.confirm(
      "Da li ste sigurni da želite da izbrišete zakazani termin?"
    );

    if (!confirmDelete) {
      return; // Korisnik nije potvrdio brisanje
    }

    try {
      const response = await axios.delete(
        `http://localhost/EduScheduler/api/delete_busyclassroom.php?id=${appointmentId}`,
        { withCredentials: true }
      );
      if (response.data.success) {
        // Remove the deleted appointment from state
        setScheduledAppointments((prevAppointments) =>
          prevAppointments.filter(
            (appointment) => appointment.id !== appointmentId
          )
        );
      } else {
        console.error("Failed to delete appointment:", response.data.message);
      }
    } catch (error) {
      console.error("Error deleting appointment:", error);
    }
  };

  const formatDate = (dateString) => {
    const options = { day: "2-digit", month: "2-digit", year: "numeric" };
    return new Date(dateString).toLocaleDateString("sr-RS", options);
  };

  if (!isLoggedIn) {
    return (
      <p>Molimo vas da se ulogujete da biste videli vaše zakazane termine.</p>
    );
  }

  if (loading) {
    return <p>Loading...</p>;
  }

  return (
    <div className="left-sidebar">
      <h2 className="appointments-heading">Moji zakazani termini</h2>
      {scheduledAppointments.length > 0 ? (
        <ul>
          {scheduledAppointments.map((appointment) => (
            <li key={appointment.id} className="appointments">
              <span>
                {appointment.amphitheater} od {appointment.startTime}h do{" "}
                {appointment.endTime}h za {formatDate(appointment.calendar)}
              </span>
              <button
                onClick={() => handleDeleteAppointment(appointment.id)}
                className="delete-button-appointments"
              >
                Obriši
              </button>
            </li>
          ))}
        </ul>
      ) : (
        <p className="appointments-paragraph">Nema mojih zakazanih termina</p>
      )}
    </div>
  );
};

export default LeftSidebar;
