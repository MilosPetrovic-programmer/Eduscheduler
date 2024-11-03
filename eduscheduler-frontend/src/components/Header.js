import React, { useState, useEffect } from "react";
import axios from "axios";
import "./Header.css";
import "./LoginForm.css";
import logo from "../img/logo.png";
import LoginForm from "./LoginForm";

const Header = () => {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [professorName, setProfessorName] = useState("");
  const [modalIsOpen, setModalIsOpen] = useState(false);

  useEffect(() => {
    // Provjeravanje statusa prijave prilikom učitavanja komponenti
    const checkLoginStatus = async () => {
      try {
        const response = await axios.get(
          "http://localhost/EduScheduler/api/check_login.php",
          {
            withCredentials: true, // Omogućavanje slanja kolačića sa sesijom
          }
        );
        setIsLoggedIn(response.data.loggedIn);
        if (response.data.loggedIn) {
          setProfessorName(response.data.name);
        }
      } catch (error) {
        console.error("Error checking login status:", error);
      }
    };

    checkLoginStatus();
  }, []);

  const openModal = () => {
    setModalIsOpen(true);
  };

  const closeModal = (name) => {
    setModalIsOpen(false);
    setIsLoggedIn(true); // Ažuriranje stanja prijave nakon uspješne prijave
    setProfessorName(name);
  };

  const handleLogout = async () => {
    try {
      await axios.post(
        "http://localhost/EduScheduler/logout.php",
        {},
        { withCredentials: true }
      );
      setIsLoggedIn(false); // Ažuriranje stanja prijave nakon odjave
      setProfessorName("");
      window.location.reload();
    } catch (error) {
      console.error("Error logging out:", error);
    }
  };

  return (
    <header className="site-header">
      <div className="site-identity">
        <a href="#">
          <img src={logo} alt="ATVSS" />
        </a>
        <h1>
          <a href="#">ATVSS</a>
        </h1>
      </div>
      <nav className="site-navigation">
        <ul className="nav">
          <li>
            {isLoggedIn ? (
              <>
                <span>Dobrodošli, {professorName}!</span>
                <button onClick={handleLogout}>Odjavi se</button>
              </>
            ) : (
              <button onClick={openModal}>Uloguj se</button>
            )}
          </li>
        </ul>
      </nav>
      {modalIsOpen && <LoginForm closeModal={(name) => closeModal(name)} />}
    </header>
  );
};

export default Header;
