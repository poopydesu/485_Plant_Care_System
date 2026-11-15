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
    empty.textContent = "No plants yet. Press + to add one, or log in to see your plants.";
    list.appendChild(empty);
  }
  
  plants.forEach(p => {
    const li = document.createElement("li");
    li.setAttribute("role", "option");
    li.setAttribute("aria-selected", p.plant_id === selectedId);

    if (p.image) {
      const img = document.createElement("img");
      img.src = p.image;
      img.alt = p.common_name;
      img.className = "thumb";
      li.appendChild(img);
    }

    const text = document.createElement("div");
    text.className = "plant-text";
    const common = document.createElement("div");
    common.className = "name";
    common.textContent = p.common_name;
    const sci = document.createElement("div");
    sci.className = "meta";
    sci.textContent = p.scientific_name;
    text.append(common, sci);
    li.appendChild(text);

    const more = document.createElement("a");
    more.href = "plant-info.html?id=" + encodeURIComponent(p.plant_id);
    more.className = "more";
    more.addEventListener("click", e => e.stopPropagation()); // don't also select the row
    li.appendChild(more);

    li.addEventListener("click", () => {
      selectedId = (selectedId === p.plant_id) ? null : p.plant_id;
      render();
    });
    list.appendChild(li);
  });
  removeBtn.disabled = editBtn.disabled = selectedId === null;
}

function openDialog(plant) {
  editingId = plant ? plant.plant_id : null;
  document.getElementById("dialogTitle").textContent = plant ? "Edit plant" : "Add plant";
  document.getElementById("fSName").value = plant ? plant.scientific_name : "";
  document.getElementById("fCName").value = plant ? plant.common_name : "";
  document.getElementById("fImg").value = "";
  dialog.showModal();
}

addBtn.addEventListener("click", () => openDialog(null));

editBtn.addEventListener("click", () => {
  const p = plants.find(x => x.plant_id === selectedId);
  if (p) openDialog(p);
});

removeBtn.addEventListener("click", async () => {
  const p = plants.find(x => x.plant_id === selectedId);
  if (!p || !confirm("Remove " + p.common_name + "?")) return;
  const fd = new FormData();
  fd.append("action", "delete");
  fd.append("id", p.plant_id);
  try {
    await api(fd);
    plants = plants.filter(x => x.plant_id !== p.plant_id);
    selectedId = null;
    render();
  } catch (e) { alert(e.message); }
});

document.getElementById("cancelBtn").addEventListener("click", () => dialog.close());

form.addEventListener("submit", async () => {
  const scientific_name = document.getElementById("fSName").value.trim();
  const common_name = document.getElementById("fCName").value.trim();
  const file = document.getElementById("fImg").files[0];

  const fd = new FormData();
  fd.append("action", editingId === null ? "add" : "edit");
  if (editingId !== null) fd.append("id", editingId);
  fd.append("scientific_name", scientific_name);
  fd.append("common_name", common_name);
  if (file) fd.append("image", file);

  try {
    const r = await api(fd);
    if (editingId === null) {
      plants.push({ plant_id: r.id, scientific_name, common_name, image: r.image });
      selectedId = r.id;
    } else {
      Object.assign(plants.find(x => x.plant_id === editingId),
                    { scientific_name, common_name, image: r.image });
    }
    render();
  } catch (e) { alert(e.message); }
});

render();