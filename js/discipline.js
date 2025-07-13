document.addEventListener("DOMContentLoaded", function () {
  const subjectModal = document.getElementById("subjectModal");
  subjectModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;
    const department = button.closest(".card").getAttribute("data-department");
    const modalTitle = subjectModal.querySelector("#modalDepartment");
    const subjectList = subjectModal.querySelector("#subjectList");

    modalTitle.textContent = `Subjects for ${department
      .replace("_", " ")
      .toUpperCase()}`;

    const filteredSubjects = window.subjectData.filter(
      (subject) => subject.department === department
    );

    subjectList.innerHTML = "";
    filteredSubjects.forEach((subject) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${subject.id}</td>
                <td>${subject.subject_name}</td>
                <td>${subject.semester}</td>
            `;
      subjectList.appendChild(row);
    });
  });
});
