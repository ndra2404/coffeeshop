let isCetak = false,
    produk = [],
    transaksi = $("#transaksi").DataTable({
        responsive: true,
        lengthChange: false,
        searching: false,
        scrollX: true
    });

function reloadTable() {
    transaksi.ajax.reload()
}

function nota(jumlah) {
    let hasil = "",
        char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        total = char.length;
    for (var r = 0; r < jumlah; r++) hasil += char.charAt(Math.floor(Math.random() * total));
    return hasil
}

function getNama() {
    $.ajax({
        url: produkGetNamaUrl,
        type: "post",
        dataType: "json",
        data: {
            id: $("#barcode").val()
        },
        success: res => {
			if(res.status_menu=="Tersedia"){
				$("#nama_produk").html(`${res.nama_menu} saat ini <span style="color:green;font-weight:bold">${res.status_menu}</span>`);
				$("#jumlah").removeAttr("disabled", "disabled")
			}else{
				$("#nama_produk").html(`${res.nama_menu} saat ini <span style="color:red;font-weight:bold">${res.status_menu}</span>`);
				$("#tambah").attr("disabled", "disabled");
				$("#jumlah").attr("disabled", "disabled")
			}
           
            //$("#sisa").html(`Sisa ${res.status_menu}`);
            checkEmpty()
        },
        error: err => {
            console.log(err)
        }
    })
}

function checkStok() {
    $.ajax({
        url: produkGetStokUrl,
        type: "post",
        dataType: "json",
        data: {
            id: $("#barcode").val()
        },
        success: res => {
            let barcode = $("#barcode").val(),
                nama_produk = res.nama_menu,
                jumlah = parseInt($("#jumlah").val()),
                stok = parseInt(res.stok),
                harga = parseInt(res.harga),
                dataBarcode = res.barcode,
                total = parseInt($("#total").html());
            if (stok < jumlah) Swal.fire("Gagal", "Stok Tidak Cukup", "warning");
            else {
                let a = transaksi.rows().indexes().filter((a, t) => dataBarcode === transaksi.row(a).data()[0]);
                if (a.length > 0) {
                    let row = transaksi.row(a[0]),
                        data = row.data();
                    if (stok < data[3] + jumlah) {
                        Swal.fire('stok', "Stok Tidak Cukup", "warning")
                    } else {
                        data[3] = data[3] + jumlah;
                        row.data(data).draw();
                        indexProduk = produk.findIndex(a => a.id == barcode);
                        produk[indexProduk].stok = stok - data[3];
                        $("#total").html(total + harga * jumlah)
                    }
                } else {
                    produk.push({
                        id: barcode,
                        stok: stok - jumlah,
                        terjual: jumlah,
						harga: harga
                    });
                    transaksi.row.add([
                        dataBarcode,
                        nama_produk,
                        harga,
                        jumlah,
                        `<button name="${barcode}" class="btn btn-sm btn-danger" onclick="remove('${barcode}')">Hapus</btn>`]).draw();
                    $("#total").html(total + harga * jumlah);
                    $("#jumlah").val("");
                    $("#tambah").attr("disabled", "disabled");
                    $("#bayar").removeAttr("disabled")
                } 
            }
        }
    })
}

function bayarCetak() {
    isCetak = true
}

function bayar() {
    isCetak = false
}

function checkEmpty() {
    let barcode = $("#barcode").val(),
        jumlah = $("#jumlah").val();
    if (barcode !== "" && jumlah !== "" && parseInt(jumlah) >= 1) {
        $("#tambah").removeAttr("disabled")    
    } else {
        $("#tambah").attr("disabled", "disabled")
    }
}

function checkUang() {
    let jumlah_uang = $('[name="jumlah_uang"').val(),
        total_bayar = parseInt($(".total_bayar").html());
		if($("#pelanggans").val()=="Bayar"){
			if (jumlah_uang !== "" && jumlah_uang >= total_bayar) {
				$("#add").removeAttr("disabled");
				$("#cetak").removeAttr("disabled")
			} else {
				$("#add").attr("disabled", "disabled");
				$("#cetak").attr("disabled", "disabled")
			}
		}else{
			$("#add").removeAttr("disabled");
			$("#add").html("Simpan");
			//$("#cetak").removeAttr("disabled")
		}
}

function remove(nama) {
    let data = transaksi.row($("[name=" + nama + "]").closest("tr")).data(),
        stok = data[3],
        harga = data[2],
        total = parseInt($("#total").html());
        akhir = total - stok * harga
    $("#total").html(akhir);
    transaksi.row($("[name=" + nama + "]").closest("tr")).remove().draw();
    $("#tambah").attr("disabled", "disabled");
    if (akhir < 1) {
        $("#bayar").attr("disabled", "disabled")
    }
}

function add() {
    let data = transaksi.rows().data(),
        qty = [];
    $.each(data, (index, value) => {
        qty.push(value[3])
    });
    $.ajax({
        url: addUrl+"/"+$("#no_order").val(),
        type: "post",
        dataType: "json",
        data: {
            produk: JSON.stringify(produk),
            tanggal: $("#tanggal").val(),
            qty: qty,
            total_bayar: $("#total").html(),
            jumlah_uang: $('[name="jumlah_uang"]').val(),
            diskon: $('[name="diskon"]').val(),
            pelanggan: $('[name="pelanggan"]').val(),
			status: $("#pelanggans").val(),
            nota: $("#nota").html()
        },
        success: res => {

            if (isCetak) {
                Swal.fire("Sukses", "Sukses Membayar", "success").
                    then(() => window.location.href = `${cetakUrl}${res}`)
            } else {
                Swal.fire("Sukses", "Sukses Membayar", "success").
                    then(() => window.location.reload())
            }
        },
        error: err => {
            console.log(err)
        }
    })
}

function kembalian() {
    let total = $("#total").html(),
        jumlah_uang = $('[name="jumlah_uang"').val(),
        diskon = $('[name="diskon"]').val();
    $(".kembalian").html(jumlah_uang - total - diskon);
    checkUang()
}

$(".modal").on("hidden.bs.modal", () => {
    $("#form")[0].reset();
    $("#form").validate().resetForm()
});
$(".modal").on("show.bs.modal", () => {
    let total = $("#total").html(),
        jumlah_uang = $('[name="jumlah_uang"').val();
    $(".total_bayar").html(total), $(".kembalian").html(Math.max(jumlah_uang - total, 0))
});


$("#formbayar").validate({
    errorElement: "span",
    errorPlacement: (err, el) => {
        err.addClass("invalid-feedback"), el.closest(".form-group").append(err)
    },
    submitHandler: () => {
        add()
    }
});
$("#nota").html(nota(15));
