import React, { useState, useEffect } from "react";
import {
  BrowserRouter as Router,
  Route,
  Routes,
  useLocation,
} from "react-router-dom";
import axios from "axios";
import FirstFloor from "./components/FirstFloor";
import SecondFloor from "./components/SecondFloor";
import ThirdFloor from "./components/ThirdFloor";
import Header from "./components/Header";
import RightSideBar from "./components/RightSideBar";
import LeftSidebar from "./components/LeftSidebar";
import AdminPanel from "./components/AdminPanel";
import ResetPassword from "./components/ResetPassword"; // Uvoz ResetPassword komponente
import "./index.css";

function MainContent({ isLoggedIn }) {
  const location = useLocation();
  const [selectedFloor, setSelectedFloor] = useState(() => {
    const savedFloor = localStorage.getItem("selectedFloor");
    return savedFloor ? parseInt(savedFloor, 10) : 1;
  });

  const handleFloorSelect = (floor) => {
    setSelectedFloor(floor);
    localStorage.setItem("selectedFloor", floor);
  };

  const renderFloor = () => {
    switch (selectedFloor) {
      case 1:
        return <FirstFloor />;
      case 2:
        return <SecondFloor />;
      case 3:
        return <ThirdFloor />;
      default:
        return <FirstFloor />;
    }
  };

  return (
    <div className="App">
      {location.pathname !== "/admin" && location.pathname !== "/reset-password" && <Header />}
      <Routes>
        <Route
          path="/"
          element={
            <div>
              <div className="floors">
                <span onClick={() => handleFloorSelect(1)}>I Sprat</span> |{" "}
                <span onClick={() => handleFloorSelect(2)}>II Sprat</span> |{" "}
                <span onClick={() => handleFloorSelect(3)}>III Sprat</span>
              </div>
              <div className="main-content">
                {isLoggedIn && <LeftSidebar isLoggedIn={isLoggedIn} />}
                {renderFloor()}
                <RightSideBar />
              </div>
            </div>
          }
        />
        <Route path="/admin" element={<AdminPanel />} />
        <Route path="/reset-password" element={<ResetPassword />} /> {/* Dodata ruta za resetovanje šifre */}
      </Routes>
    </div>
  );
}

function App() {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const checkLoginStatus = async () => {
      try {
        const response = await axios.get(
          "http://localhost/EduScheduler/api/check_login.php",
          { withCredentials: true }
        );
        setIsLoggedIn(response.data.loggedIn);
      } catch (error) {
        console.error("Error checking login status:", error);
      } finally {
        setLoading(false);
      }
    };

    checkLoginStatus();
  }, []);

  if (loading) {
    return <p>Loading...</p>;
  }

  return (
    <Router>
      <MainContent isLoggedIn={isLoggedIn} />
    </Router>
  );
}

export default App;
