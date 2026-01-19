  function toggleDropdown(id, element) {
    // Hide other dropdowns
    document.querySelectorAll('[id$="Dropdown"]').forEach(drop => {
      if (drop.id !== id) drop.classList.add('hidden');
    });

    const dropdown = document.getElementById(id);
    dropdown.classList.toggle('hidden');
  }


//   ........................................... 
   // ........................................................

    // dropdown sidebar 


    


