import React, { useState } from "react";
import { useSearchParams } from "react-router-dom";
import axios from "axios";

function ResetPassword() {
  const [newPassword, setNewPassword] = useState("");
  const [message, setMessage] = useState("");
  const [searchParams] = useSearchParams();
  const token = searchParams.get("token");

  const handlePasswordReset = async () => {
  console.log("Token: ", token);
  console.log("Nova šifra: ", newPassword);

  try {
    const response = await axios.post("http://localhost/EduScheduler/admin/reset_password.php", {
      token,
      newPassword,
    });
    console.log("Response: ", response.data);
    setMessage(response.data.message);
  } catch (error) {
    console.error("Error: ", error);
    setMessage("Došlo je do greške prilikom resetovanja šifre.");
  }
};


  return (
    <div>
      <h1>Resetovanje Šifre</h1>
      <input
        type="password"
        placeholder="Nova šifra"
        value={newPassword}
        onChange={(e) => setNewPassword(e.target.value)}
      />
      <button onClick={handlePasswordReset}>Postavi novu šifru</button>
      {message && <p>{message}</p>}
    </div>
  );
}

export default ResetPassword;
