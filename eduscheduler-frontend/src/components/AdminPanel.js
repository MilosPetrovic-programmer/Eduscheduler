import React, { useState, useEffect } from "react";
import axios from "axios";
import Modal from "react-modal";
import "./AdminPanel.css";
import "./LoginForm.css";

Modal.setAppElement("#root");

const AdminPanel = () => {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  const [modalIsOpen, setModalIsOpen] = useState(false);
  const [modalContent, setModalContent] = useState("");
  const [formData, setFormData] = useState({});
  const [classrooms, setClassrooms] = useState([]);
  const [professors, setProfessors] = useState([]);

  useEffect(() => {
    const loggedInStatus = localStorage.getItem("isLoggedIn");
    if (loggedInStatus === "true") {
      setIsLoggedIn(true);
      fetchClassrooms();
      fetchProfessors();
    }
  }, []);

  const fetchClassrooms = async () => {
    try {
      const response = await axios.get(
        "http://localhost/EduScheduler/api/classrooms.php"
      );
      setClassrooms(response.data);
    } catch (error) {
      console.error("Error fetching classrooms:", error);
    }
  };

  const fetchProfessors = async () => {
    try {
      const response = await axios.get(
        "http://localhost/EduScheduler/api/professors.php"
      );
      setProfessors(response.data);
    } catch (error) {
      console.error("Error fetching professors:", error);
    }
  };

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post(
        "http://localhost/EduScheduler/admin/admin_login.php",
        { username, password },
        { withCredentials: true }
      );
      if (response.data.success) {
        alert(`Prijava uspešna: ${response.data.username}`);
        localStorage.setItem("isLoggedIn", "true");
        setIsLoggedIn(true);
      } else {
        alert(response.data.message);
      }
    } catch (error) {
      console.error("Došlo je do pogreške pri prijavi:", error);
    }
  };

  const handleLogout = () => {
    localStorage.removeItem("isLoggedIn");
    setIsLoggedIn(false);
    setUsername("");
    setPassword("");
  };

  const openModal = (content, data = {}) => {
    setModalContent(content);
    setFormData(data);
    setModalIsOpen(true);
  };

  const closeModal = () => {
    setModalIsOpen(false);
    setFormData({});
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({
      ...prevData,
      [name]: value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    let url = "";
    switch (modalContent) {
      case "Dodaj profesora":
        url = "http://localhost/EduScheduler/admin/add_professor.php";
        break;
      case "Izmeni profesora":
        url = "http://localhost/EduScheduler/admin/update_professor.php";
        break;
      case "Izmeni učionicu":
        url = "http://localhost/EduScheduler/admin/update_classrooms.php";
        break;
      case "Dodaj učionicu":
        url = "http://localhost/EduScheduler/admin/add_classroom.php";
        break;
      case "Dodaj admina":
        url = "http://localhost/EduScheduler/admin/add_admin.php";
        break;
      default:
        return;
    }

    if (Object.keys(formData).length === 0) {
      console.error("Nema podataka za slanje");
      return;
    }

    console.log("Slanje podataka serveru:", formData);

    try {
      const response = await axios.post(url, formData, {
        headers: { "Content-Type": "application/json" },
        withCredentials: true,
      });

      console.log("Odgovor servera:", response.data);

      alert(response.data.message);
      if (response.data.success) {
        closeModal();
        fetchClassrooms();
        fetchProfessors();
      }
    } catch (error) {
      console.error("Došlo je do pogreške pri izvršavanju akcije:", error);
    }
  };

  const handleDelete = async (id, type) => {
    if (!window.confirm("Da li ste sigurni da želite da obrišete ovaj unos?")) {
      return;
    }

    let url = "";
    switch (type) {
      case "professor":
        url = `http://localhost/EduScheduler/admin/delete_professor.php?id=${id}`;
        break;
      case "classroom":
        url = `http://localhost/EduScheduler/admin/delete_classroom.php?id=${id}`;
        break;
      default:
        return;
    }

    try {
      const response = await axios.delete(url, { withCredentials: true });
      console.log(response.data); // Dodato logovanje
      alert(response.data.message);
      if (response.data.success) {
        fetchClassrooms();
        fetchProfessors();
      }
    } catch (error) {
      console.error("Došlo je do pogreške pri brisanju unosa:", error);
    }
  };
  const sendPasswordResetEmail = async (email) => {
  if (!email || !/\S+@\S+\.\S+/.test(email)) {
    alert("Unesite validnu email adresu.");
    return;
  }

  try {
    const response = await axios.post(
      "http://localhost/EduScheduler/admin/send_password_reset.php",
      { email },
      { headers: { "Content-Type": "application/json" } }
    );

    if (response.data.success) {
      alert(response.data.message);
    } else {
      alert("Došlo je do greške: " + response.data.message);
    }
  } catch (error) {
    console.error("Greška pri slanju emaila:", error);
    alert("Došlo je do greške pri slanju emaila: " + error.message);
  }
};


  return (
    <div className="admin-panel">
      {isLoggedIn ? (
        <div className="admin-actions">
          <div
            className="action-block"
            onClick={() => openModal("Dodaj profesora")}
          >
            <h2>Dodaj profesora</h2>
          </div>
          <div
            className="action-block"
            onClick={() => openModal("Dodaj učionicu")}
          >
            <h2>Dodaj učionicu</h2>
          </div>
          <div
            className="action-block"
            onClick={() => openModal("Dodaj admina")}
          >
            <h2>Dodaj admina</h2>
          </div>
          <button className="logout-button" onClick={handleLogout}>
            Odjavi se
          </button>
        </div>
      ) : (
        <div className="login-form">
          <h2>Login</h2>
          <form onSubmit={handleLogin}>
            <input
              type="text"
              placeholder="Korisničko ime"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              required
            />
            <input
              type="password"
              placeholder="Šifra"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
            <button type="submit">Prijavi se</button>
          </form>
        </div>
      )}

      <Modal
        isOpen={modalIsOpen}
        onRequestClose={closeModal}
        contentLabel="Admin Modal"
        className="modal"
        overlayClassName="modal-overlay"
      >
        <button onClick={closeModal}>Zatvori</button>
        <div>{modalContent}</div>
        <form onSubmit={handleSubmit}>
          {modalContent === "Dodaj profesora" && (
            <>
              <input
                type="text"
                name="name"
                placeholder="Ime"
                value={formData.name || ""}
                onChange={handleChange}
                required
              />
              <input
                type="text"
                name="surname"
                placeholder="Prezime"
                value={formData.surname || ""}
                onChange={handleChange}
                required
              />
              <input
                type="text"
                name="email"
                placeholder="Email"
                value={formData.email || ""}
                onChange={handleChange}
                required
              />
              <input
                type="password"
                name="password"
                placeholder="Šifra"
                value={formData.password || ""}
                onChange={handleChange}
                required
              />
            </>
          )}
          {modalContent === "Izmeni profesora" && (
            <>
              <input
                type="text"
                name="name"
                placeholder="Ime"
                value={formData.name || ""}
                onChange={handleChange}
                required
              />
              <input
                type="text"
                name="surname"
                placeholder="Prezime"
                value={formData.surname || ""}
                onChange={handleChange}
                required
              />
              <input
                type="text"
                name="email"
                placeholder="Email"
                value={formData.email || ""}
                onChange={handleChange}
                required
              />
              <input
                type="password"
                name="password"
                placeholder="Šifra"
                value={formData.password || ""}
                onChange={handleChange}
              />
            </>
          )}
          {(modalContent === "Izmeni učionicu" ||
            modalContent === "Dodaj učionicu") && (
            <>
              <input
                type="text"
                name="classroom"
                placeholder="Učionica"
                value={formData.classroom || ""}
                onChange={handleChange}
                required
              />
              <input
                type="text"
                name="features"
                placeholder="Funkcije"
                value={formData.features || ""}
                onChange={handleChange}
                required
              />
              <input
                type="text"
                name="floor"
                placeholder="Sprat"
                value={formData.floor || ""}
                onChange={handleChange}
                required
              />
            </>
          )}
          {modalContent === "Dodaj admina" && (
            <>
              <input
                type="text"
                name="username"
                placeholder="Korisničko ime"
                value={formData.username || ""}
                onChange={handleChange}
                required
              />
              <input
                type="password"
                name="password"
                placeholder="Šifra"
                value={formData.password || ""}
                onChange={handleChange}
                required
              />
            </>
          )}
          <button type="submit">Potvrdi</button>
        </form>
      </Modal>

      {isLoggedIn && classrooms.length > 0 && (
        <div className="classroom-list">
          <h2 className="headings">Učionice</h2>
          {classrooms.map((classroom) => (
            <div key={classroom.id} className="classroom-item">
              <div>
                <strong>Učionica:</strong> {classroom.classroom}
              </div>
              <div>
                <strong>Funkcije:</strong> {classroom.features}
              </div>
              <div>
                <strong>Sprat:</strong> {classroom.floor}
              </div>
              <button onClick={() => openModal("Izmeni učionicu", classroom)}>
                Izmeni
              </button>
              <button
                className="delete-button"
                onClick={() => handleDelete(classroom.id, "classroom")}
              >
                Obriši
              </button>
            </div>
          ))}
        </div>
      )}

      {isLoggedIn && professors.length > 0 && (
        <div className="professor-list">
          <h2 className="headings">Profesori</h2>
          {professors.map((professor) => (
  <div key={professor.id} className="professor-item">
    <div>
      <strong>Ime:</strong> {professor.name}
    </div>
    <div>
      <strong>Prezime:</strong> {professor.surname}
    </div>
    <div>
      <strong>Email:</strong> {professor.email}
    </div>
    <button
      className="edit-button"
      onClick={() => openModal("Izmeni profesora", professor)}
    >
      Izmeni
    </button>
    <button
      className="delete-button"
      onClick={() => handleDelete(professor.id, "professor")}
    >
      Obriši
    </button>
    <button
      className="send-email-button"
      onClick={() => sendPasswordResetEmail(professor.email)}
    >
      Pošalji email
    </button>
  </div>
))}
        </div>
      )}
    </div>
  );
};

export default AdminPanel;
