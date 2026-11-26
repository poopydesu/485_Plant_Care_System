let plants = window.PLANTS || [];
let selectedId = null;
let editingId = null;

const list = document.getElementById("plantList");
const addBtn = document.getElementById("addBtn");
const removeBtn = document.getElementById("removeBtn");
const editBtn = document.getElementById("editBtn");
const dialog = document.getElementById("plantDialog");
const form = document.getElementById("plantForm");

async function api(fd) {
  const res = await fetch("plants_api.php", { method: "POST", body: fd });
  const data = await res.json().catch(() => ({}));
  if (!res.ok) throw new Error(data.error || "Request failed.");
  return data;
}

function render() {
  list.innerHTML = "";
  if (plants.length === 0) {
    const empty = document.createElement("li");
    empty.className = "empty";
    empty.textContent = "No plants yet. Press + to add one.";
    list.appendChild(empty);
  }
  
  plants.forEach(p => {
    const li = document.createElement("li");
    li.setAttribute("role", "option");
    li.setAttribute("aria-selected", p.user_plant_id === selectedId);

    if (p.image) {
      const img = document.createElement("img");
      img.src = p.image;
      img.alt = p.custom_name;
      img.className = "thumb";
      li.appendChild(img);
    }

    const text = document.createElement("div");
    text.className = "plant-text";
    const common = document.createElement("div");
    common.className = "name";
    common.textContent = p.custom_name;
    const sci = document.createElement("div");
    sci.className = "meta";
    sci.textContent = p.scientific_name || "Other / not listed";
    text.append(common, sci);
    li.appendChild(text);

    // Link to the plant's care guide, if the user picked a species for it
    if (p.species_id) {
      const more = document.createElement("a");
      more.href = "plant-info.php?id=" + encodeURIComponent(p.species_id);
      more.className = "more";
      more.textContent = "Care guide";
      more.addEventListener("click", e => e.stopPropagation()); // don't also select the row
      li.appendChild(more);
    }

    li.addEventListener("click", () => {
      selectedId = (selectedId === p.user_plant_id) ? null : p.user_plant_id;
      render();
    });
    list.appendChild(li);
  });
  removeBtn.disabled = editBtn.disabled = selectedId === null;
}

function openDialog(plant) {
  editingId = plant ? plant.user_plant_id : null;
  document.getElementById("dialogTitle").textContent = plant ? "Edit plant" : "Add plant";
  document.getElementById("fName").value = plant ? plant.custom_name : "";
  document.getElementById("fSpecies").value = plant && plant.species_id ? plant.species_id : "";
  document.getElementById("fImg").value = "";
  dialog.showModal();
}

addBtn.addEventListener("click", () => openDialog(null));

editBtn.addEventListener("click", () => {
  const p = plants.find(x => x.user_plant_id === selectedId);
  if (p) openDialog(p);
});

removeBtn.addEventListener("click", async () => {
  const p = plants.find(x => x.user_plant_id === selectedId);
  if (!p || !confirm("Remove " + p.custom_name + "?")) return;
  const fd = new FormData();
  fd.append("action", "delete");
  fd.append("id", p.user_plant_id);
  try {
    await api(fd);
    plants = plants.filter(x => x.user_plant_id !== p.user_plant_id);
    selectedId = null;
    render();
  } catch (e) { alert(e.message); }
});

document.getElementById("cancelBtn").addEventListener("click", () => dialog.close());

form.addEventListener("submit", async () => {
  const custom_name = document.getElementById("fName").value.trim();
  const species_id = document.getElementById("fSpecies").value;
  const file = document.getElementById("fImg").files[0];

  const fd = new FormData();
  fd.append("action", editingId === null ? "add" : "edit");
  if (editingId !== null) fd.append("id", editingId);
  fd.append("custom_name", custom_name);
  fd.append("species_id", species_id);
  if (file) fd.append("image", file);

  try {
    await api(fd);
    window.location.reload();   // reload so the list (species name, image) comes fresh from the database
  } catch (e) { alert(e.message); }
});

render();