// document
//   .getElementById("updateForm")
//   .addEventListener("submit", async function (event) {
//     event.preventDefault();
//     const form = event.target;
//     const formData = new FormData(form);

//     try {
//       const response = await fetch(form.action, {
//         method: "POST",
//         body: formData,
//       });
//       const data = await response.json();

//       if (response.ok) {
//         document.getElementById("message").textContent = data.message;
//         window.location.href = "login.html";
//       } else {
//         document.getElementById("message").textContent = data.message;
//       }
//     } catch (error) {
//       console.error("Error:", error);
//     }
//   });
