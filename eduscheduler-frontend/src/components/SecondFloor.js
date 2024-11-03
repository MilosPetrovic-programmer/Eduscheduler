import React, { useState, useEffect } from "react";
import axios from "axios";
import ReactModal from "react-modal";
import "./SecondFloor.css";

ReactModal.setAppElement("#root"); // Set the root element for accessibility

const SecondFloor = () => {
  const [classrooms, setClassrooms] = useState([]);
  const [busyClassrooms, setBusyClassrooms] = useState([]);
  const [modalIsOpen, setModalIsOpen] = useState(false);
  const [selectedRoom, setSelectedRoom] = useState(null);
  const [date, setDate] = useState("");
  const [startTime, setStartTime] = useState("");
  const [endTime, setEndTime] = useState("");
  const [busyIntervals, setBusyIntervals] = useState([]);
  const [freeIntervals, setFreeIntervals] = useState([]);

  useEffect(() => {
    const today = new Date().toISOString().split("T")[0];
    setDate(today);
  }, []);

  useEffect(() => {
    const fetchClassrooms = async () => {
      try {
        const response = await axios.get(
          "http://localhost/EduScheduler/api/classrooms.php"
        );
        setClassrooms(response.data);
        console.log("Classrooms data:", response.data);
      } catch (error) {
        console.error("Error fetching classrooms:", error);
      }
    };

    const fetchBusyClassrooms = async () => {
      try {
        const response = await fetch(
          "http://localhost/EduScheduler/api/check_classrooms.php"
        );
        const data = await response.json();
        setBusyClassrooms(data);
        console.log("Busy classrooms data:", data);
      } catch (error) {
        console.error("Error fetching busy classrooms:", error);
      }
    };

    fetchClassrooms();
    fetchBusyClassrooms();
    const interval = setInterval(fetchBusyClassrooms, 10000); // Refresh every 60 seconds

    return () => clearInterval(interval); // Cleanup interval on component unmount
  }, []);

  const openModal = async (roomName) => {
    const room = classrooms.find(
      (classroom) => classroom.classroom === roomName
    );
    setSelectedRoom(room);
    setModalIsOpen(true);

    const today = new Date().toISOString().split("T")[0];
    setDate(today);
    await sendBookingDataToBackend(today, roomName);
  };

  const closeModal = () => {
    const today = new Date().toISOString().split("T")[0];
    setDate(today);
    setModalIsOpen(false);
    setSelectedRoom(null);
    setStartTime("");
    setEndTime("");
    setBusyIntervals([]);
    setFreeIntervals([]);
  };

  const handleDateChange = (e) => {
    const selectedDate = e.target.value;
    setDate(selectedDate);
    if (selectedRoom) {
      sendBookingDataToBackend(selectedDate, selectedRoom.classroom);
    }
  };

  const sendBookingDataToBackend = async (selectedDate, roomName) => {
    try {
      const bookingData = {
        date: selectedDate,
        roomName: roomName,
      };

      console.log("Sending booking data:", bookingData);

      const response = await axios.post(
        "http://localhost/EduScheduler/api/intervals.php",
        bookingData,
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      );

      console.log("Booking data sent:", response.data);
      setBusyIntervals(response.data.busyIntervals); // Update state with busy intervals
      setFreeIntervals(response.data.freeIntervals); // Update state with free intervals
    } catch (error) {
      console.error("Error sending booking data:", error);
    }
  };

  const handleFormSubmit = async (event) => {
    event.preventDefault();

    if (!date || !startTime || !endTime || !selectedRoom) {
      alert("Molimo popunite sve podatke.");
      return;
    }

    // Check for overlapping intervals
    const newStartTime = parseInt(startTime, 10);
    const newEndTime = parseInt(endTime, 10);

    const hasOverlap = busyIntervals.some((interval) => {
      const busyStart = parseInt(interval.startTime, 10);
      const busyEnd = parseInt(interval.endTime, 10);
      return newStartTime < busyEnd && newEndTime > busyStart;
    });

    if (hasOverlap) {
      alert(
        "Ne možete rezervisati u ovom terminu jer se preklapa sa zauzetim terminom."
      );
      return;
    }

    try {
      const bookingData = {
        date,
        startTime,
        endTime,
        amphitheater: selectedRoom.classroom,
        professor: sessionStorage.getItem("name"),
      };

      console.log("Sending booking data:", bookingData);

      const response = await axios.post(
        "http://localhost/EduScheduler/api/busy_insert.php",
        bookingData,
        {
          headers: {
            "Content-Type": "application/json",
          },
          withCredentials: true, // Dodavanje ovog omogućava slanje kolačića za sesiju
        }
      );

      console.log("Booking data sent:", response.data);

      alert(response.data.message);

      if (response.data.success) {
        closeModal();
      }
    } catch (error) {
      console.error("Error submitting form:", error);
      if (error.response) {
        console.error("Error response:", error.response);
      } else {
        console.error("Error details:", error.message);
      }
      alert(
        "Niste ulogovani, morate biti ulogovani da bi ste rezervisali termin!"
      );
    }
  };

  const isRoomBusy = (roomName) => {
    return busyClassrooms.some(
      (room) => room.amphitheater === roomName || room.classroom === roomName
    );
  };

  return (
    <div className="second-floor-plan">
      {classrooms.map((room) => {
        const roomIsBusy = isRoomBusy(room.classroom);

        return (
          room.floor == 2 && (
            <div
              key={room.id}
              className={`room ${room.classroom} ${roomIsBusy ? "busy" : ""}`}
              onClick={() => openModal(room.classroom)}
            >
              {room.classroom}
            </div>
          )
        );
      })}

      <ReactModal
        isOpen={modalIsOpen}
        onRequestClose={closeModal}
        contentLabel="Room Details"
        className="modal"
        overlayClassName="overlay"
      >
        {selectedRoom ? (
          <div>
            <h2>{selectedRoom.classroom}</h2>
            <p>Osobine: {selectedRoom.features}</p>
            <form onSubmit={handleFormSubmit}>
              <div>
                <label>Datum:</label>
                <input
                  type="date"
                  name="date"
                  value={date}
                  min={new Date().toISOString().split("T")[0]} // Disable past dates
                  onChange={handleDateChange}
                  required
                />
              </div>
              <div>
                <label>Početno vreme:</label>
                <select
                  name="startTime"
                  value={startTime}
                  onChange={(e) => setStartTime(e.target.value)}
                  required
                >
                  <option value="">Izaberi početno vreme</option>
                  <option value="8">08:00</option>
                  <option value="9">09:00</option>
                  <option value="10">10:00</option>
                  <option value="11">11:00</option>
                  <option value="12">12:00</option>
                  <option value="13">13:00</option>
                  <option value="14">14:00</option>
                  <option value="15">15:00</option>
                  <option value="16">16:00</option>
                  <option value="17">17:00</option>
                  <option value="18">18:00</option>
                  <option value="19">19:00</option>
                  <option value="20">20:00</option>
                </select>
              </div>
              <div>
                <label>Završno vreme:</label>
                <select
                  name="endTime"
                  value={endTime}
                  onChange={(e) => setEndTime(e.target.value)}
                  required
                >
                  <option value="">Izaberi završno vreme</option>
                  <option value="9">09:00</option>
                  <option value="10">10:00</option>
                  <option value="11">11:00</option>
                  <option value="12">12:00</option>
                  <option value="13">13:00</option>
                  <option value="14">14:00</option>
                  <option value="15">15:00</option>
                  <option value="16">16:00</option>
                  <option value="17">17:00</option>
                  <option value="18">18:00</option>
                  <option value="19">19:00</option>
                  <option value="20">20:00</option>
                  <option value="21">21:00</option>
                </select>
              </div>
              <input
                type="hidden"
                name="amphitheater"
                value={selectedRoom.classroom}
              />
              <div>
                <strong>Slobodni intervali:</strong>
                <ul>
                  {freeIntervals.length > 0 ? (
                    freeIntervals.map((interval, index) => (
                      <li key={index}>
                        {interval.startTime}h do {interval.endTime}h
                      </li>
                    ))
                  ) : (
                    <li>Nema slobodnih intervala.</li>
                  )}
                </ul>
              </div>
              <div>
                <strong>Zauzeti Intervali:</strong>
                <ul>
                  {busyIntervals.length > 0 ? (
                    busyIntervals.map((interval, index) => (
                      <li key={index}>
                        {interval.startTime}h do {interval.endTime}h -{" "}
                        {interval.professor}
                      </li>
                    ))
                  ) : (
                    <li>Nema zauzetih intervala za ovaj datum.</li>
                  )}
                </ul>
              </div>

              <button type="submit">Zakaži</button>
            </form>
            <button onClick={closeModal}>Zatvori</button>
          </div>
        ) : (
          <p>Učitavanje...</p>
        )}
      </ReactModal>
    </div>
  );
};

export default SecondFloor;
