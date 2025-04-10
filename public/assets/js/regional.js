document.addEventListener("DOMContentLoaded", function () {
    // getCitybyProvince
    document
        .querySelector('select[name="province"]')
        .addEventListener("change", function () {
            var idProv =
                this.options[this.selectedIndex].getAttribute("data-idProv");
            var url = "/area/ajax/mappingCity/" + idProv; // Replace with the correct route
            if (idProv) {
                
                fetch(url)
                    .then((response) => response.json())
                    .then((data) => {
                        var citySelect = document.querySelector(
                            'select[name="city"]'
                        );
                        citySelect.innerHTML =
                            '<option value="" selected>- Choose City-</option>';

                        data.forEach(function (value) {
                            var option = document.createElement("option");
                            option.value = value.nama;
                            option.setAttribute("data-idCity", value.id);
                            option.textContent = value.nama;
                            citySelect.appendChild(option);
                        });
                    });
            } else {
                document.querySelector('select[name="city"]').innerHTML = "";
            }
        });

    // getDistrictbyCity
    document
        .querySelector('select[name="city"]')
        .addEventListener("change", function () {
            var idCity =
                this.options[this.selectedIndex].getAttribute("data-idCity");
            var url = "/area/ajax/mappingDistrict/" + idCity; // Replace with the correct route
            if (idCity) {
                fetch(url)
                    .then((response) => response.json())
                    .then((data) => {
                        var districtSelect = document.querySelector(
                            'select[name="district"]'
                        );
                        districtSelect.innerHTML =
                            '<option value="" selected>- Choose District-</option>';

                        data.forEach(function (value) {
                            var option = document.createElement("option");
                            option.value = value.nama;
                            option.setAttribute("data-idDistrict", value.id);
                            option.textContent = value.nama;
                            districtSelect.appendChild(option);
                        });
                    });
            } else {
                document.querySelector('select[name="district"]').innerHTML =
                    "";
            }
        });

    // getSubDistrictbyDistrict
    document
        .querySelector('select[name="district"]')
        .addEventListener("change", function () {
            var idDistrict =
                this.options[this.selectedIndex].getAttribute(
                    "data-idDistrict"
                );
            var url = "/area/ajax/mappingSubDistrict/" + idDistrict; // Replace with the correct route
            if (idDistrict) {
                fetch(url)
                    .then((response) => response.json())
                    .then((data) => {
                        var subdistrictSelect = document.querySelector(
                            'select[name="subdistrict"]'
                        );
                        subdistrictSelect.innerHTML =
                            '<option value="" selected>- Choose Subdistrict-</option>';

                        data.forEach(function (value) {
                            var option = document.createElement("option");
                            option.value = value.nama;
                            option.setAttribute("data-zipcode", value.kodepos);
                            option.textContent = value.nama;
                            subdistrictSelect.appendChild(option);
                        });
                    });
            } else {
                document.querySelector('select[name="subdistrict"]').innerHTML =
                    "";
            }
        });

    // zipcode
    document
        .querySelector('select[name="subdistrict"]')
        .addEventListener("change", function () {
            var zipcode =
                this.options[this.selectedIndex].getAttribute("data-zipcode");
            console.log(zipcode);
            document.getElementById("zipcode").value = zipcode;
        });
});
