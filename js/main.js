/* ===== Cart ===== */
function getCart(){
    return JSON.parse(localStorage.getItem("cart")) || [];
}

function saveCart(cart){
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCounter();
}

function updateCartCounter(){
    const cart = getCart();
    const counter = document.getElementById("cart-counter");
    if(counter) counter.textContent = cart.length;
}

/* إضافة للسلة */
function addToCart(id, name, price, image){
    const cart = getCart();
    cart.push({id,name,price,image});
    saveCart(cart);
    alert("تمت إضافة المنتج للسلة");
}

/* تحميل السلة */
function loadCart(){
    const cart = getCart();
    const box = document.getElementById("cart-items");
    const totalBox = document.getElementById("total-price");

    if(!box) return;

    if(cart.length === 0){
        box.innerHTML = "<p style='text-align:center'>السلة فارغة</p>";
        document.getElementById("checkoutBtn").style.display = "none";
        return;
    }

    let total = 0;
    box.innerHTML = "";

    cart.forEach((item,index)=>{
        total += Number(item.price);

        box.innerHTML += `
            <div class="cart-item">
                <img src="images/products/${item.image}">
                <div style="flex:1">
                    <strong>${item.name}</strong><br>
                    السعر: ${item.price} $
                </div>
                <button onclick="removeItem(${index})">✖</button>
            </div>
        `;
    });

    totalBox.textContent = "الإجمالي: " + total + " $";
}

/* حذف عنصر */
function removeItem(index){
    const cart = getCart();
    cart.splice(index,1);
    saveCart(cart);
    loadCart();
}

/* إرسال الطلب */
function sendOrder(e){
    e.preventDefault();

    const name = document.getElementById("name").value;
    const phone = document.getElementById("phone").value;
    const address = document.getElementById("address").value;
    const cart = getCart();

    if(cart.length === 0){
        alert("السلة فارغة");
        return;
    }

    let msg = `طلب جديد:%0A`;
    cart.forEach(item=>{
        msg += `- ${item.name} (${item.price}$)%0A`;
    });

    msg += `%0Aالاسم: ${name}%0Aالهاتف: ${phone}%0Aالعنوان: ${address}`;

    window.open(`https://wa.me/967XXXXXXXXX?text=${msg}`);

    localStorage.removeItem("cart");
}

/* تشغيل */
updateCartCounter();
loadCart();
function changeImage(src){
    document.getElementById('mainImage').src = src;
}
