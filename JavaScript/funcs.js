    (function() {

      const categories = [
        "OPD CASH", "ER-EMERGENCY", "WARD", "SEMI-PRIVATE",
        "PRIVATE", "SUITE/ICU", "HMO OPD", "HMO IPD"
      ];

      const deptMap = {
        "OPD CASH": ["Outpatient Dept", "Family Medicine", "Pediatrics OPD"],
        "ER-EMERGENCY": ["Emergency Room", "Trauma Center", "Urgent Care"],
        "WARD": ["General Ward", "Medical Ward", "Surgical Ward"],
        "SEMI-PRIVATE": ["Semi-Private Room", "Semi-Private Ward"],
        "PRIVATE": ["Private Room", "Deluxe Private"],
        "SUITE/ICU": ["ICU", "NICU", "Suite"],
        "HMO OPD": ["HMO OPD", "Accredited OPD"],
        "HMO IPD": ["HMO IPD", "Accredited Ward"]
      };

      const priceMap =  [
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
     4203.23,4203.23,4203.23,4203.23,
      ]

      function generateData(descriptionList, codePrefix) {
        let data = [];
        for (let i = 0; i < categories.length; i++) {

            let cat = categories[i];
            let deptList = deptMap[cat];

            for (let d = 0; d < deptList.length; d++){
            let department = deptList[d];
            for (let x = 0; x < descriptionList.length; x++) {
        let description = descriptionList[x];
        let price = priceMap[i];
        let itemCode = codePrefix + (10000 + i * 100 + d * 10 + x);
            data.push({
              category: cat,
              department: department,
              itemCode: itemCode,
              description: description,
              price: price
            });
        }    
          }   
        }
        return data;
      }

      // Services descriptions
      const serviceDescs = [
        "Complete Blood Count", "Chest X-Ray PA", "Salbutamol Inhaler",
        "CT Scan Head", "MRI Lower Extremity", "Physical Therapy Session",
        "ECG", "2D Echo", "Blood Typing", "Lipid Profile", "Liver Function Test",
        "Renal Function Test", "Urinalysis", "Fecalysis", "Wound Dressing",
        "Nebulization", "Oxygen Therapy", "IV Insertion", "Catheterization"
      ];

      // Medical Supplies descriptions
      const supplyDescs = [
        "Syringe 3ml", "Syringe 5ml", "IV Catheter 18G", "IV Catheter 22G",
        "Bandage Gauze", "Adhesive Tape", "Alcohol Swabs", "Surgical Gloves",
        "Face Mask", "Surgical Cap", "Surgical Gown", "Sterile Gloves",
        "Cotton Balls", "Antiseptic Solution", "Thermometer", "Blood Pressure Cuff",
        "Stethoscope", "Oxygen Mask", "Nasal Cannula", "Urine Bag"
      ];

      // Medicines descriptions
      const medicineDescs = [
        "Paracetamol 500mg tab", "Amoxicillin 500mg cap", "Ibuprofen 400mg tab",
        "Cetirizine 10mg tab", "Omeprazole 20mg cap", "Losartan 50mg tab",
        "Metformin 850mg tab", "Atorvastatin 10mg tab", "Amlodipine 5mg tab",
        "Azithromycin 500mg tab", "Ciprofloxacin 500mg tab", "Doxycycline 100mg cap",
        "Fluconazole 150mg tab", "Loratadine 10mg tab", "Mefenamic Acid 500mg cap",
        "Naproxen 250mg tab", "Prednisone 10mg tab", "Ranitidine 150mg tab",
        "Salbutamol Inhaler 100mcg", "Simvastatin 20mg tab"
      ];

      let servicesData = generateData(serviceDescs, "SRV");
      let suppliesData = generateData(supplyDescs, "SUP");
      let medicinesData = generateData(medicineDescs, "MED");

      let searchInput = document.getElementById('searchInput');
      let searchBtn = document.getElementById('searchBtn');
      let categorySelect = document.getElementById('categorySelect');
      let servicesTbody = document.getElementById('servicesTableBody');
      let suppliesTbody = document.getElementById('suppliesTableBody');
      let medicinesTbody = document.getElementById('medicinesTableBody');

      let tabButtons = document.querySelectorAll('.tab-button');
      let tabContents = document.querySelectorAll('.tab-content');

      let activeTab = 'services';

      function switchTab(tabId) {
        activeTab = tabId;

        tabButtons.forEach(btn => {
          btn.classList.remove('tab-active', 'bg-[#29842e]', 'text-white');
          if (btn.dataset.tab === tabId) {
            btn.classList.add('tab-active', 'bg-[#29842e]', 'text-white');
          }
        });

        tabContents.forEach(content => {
          content.classList.add('tab-inactive');
        });
        document.getElementById(tabId + 'Tab').classList.remove('tab-inactive');

        searchInput.value = '';
        renderActiveTab();
      }

      tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
          switchTab(this.dataset.tab);
        });
      });

      function renderActiveTab() {
        let selected = categorySelect.value;
        let searchText = searchInput.value.toLowerCase();

        let data, tbody;
        if (activeTab === 'services') {
          data = servicesData;
          tbody = servicesTbody;
        } else if (activeTab === 'supplies') {
          data = suppliesData;
          tbody = suppliesTbody;
        } else {
          data = medicinesData;
          tbody = medicinesTbody;
        }

        let filtered = [];
        for (let i = 0; i < data.length; i++) {
          if (data[i].category === selected) {
            filtered.push(data[i]);
          }
        }

        if (searchText !== '') {
          let searchFiltered = [];
          for (let i = 0; i < filtered.length; i++) {
            let item = filtered[i];
            if (item.description.toLowerCase().indexOf(searchText) !== -1) {
              searchFiltered.push(item);
            }
          }
          filtered = searchFiltered;
        }

        let html = '';
        for (let i = 0; i < filtered.length; i++) {
          let item = filtered[i];
          html += '<tr>' +
            '<td class="px-4 py-3">' + item.department + '</td>' +
            '<td class="px-4 py-3">' + item.itemCode + '</td>' +
            '<td class="px-4 py-3">' + item.description + '</td>' +
            '<td class="px-4 py-3 text-right">' + parseFloat(item.price).toFixed(2) + '</td>' +
            '</tr>';
        }

        if (filtered.length === 0) {
          html = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No matching items found.</td></tr>';
        }

        tbody.innerHTML = html;
      }

      searchInput.addEventListener('keyup', renderActiveTab);
      searchBtn.addEventListener('click', renderActiveTab);
      categorySelect.addEventListener('change', renderActiveTab);

      // Initial Render
      switchTab('services');
    })();
