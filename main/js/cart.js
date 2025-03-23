document.addEventListener("DOMContentLoaded", () => {
    const cartContainer = document.getElementById("cart-container");
    const cartTotal = document.getElementById("cart-total");
    const sidebar = document.getElementById("sidebar");
  
    let cartItems = JSON.parse(localStorage.getItem("cart")) || [];
  
    function renderCart() {
      cartContainer.innerHTML = "";
  
      if (cartItems.length === 0) {
        cartContainer.innerHTML = `<p>No items in cart.</p>`;
        cartTotal.textContent = "$0.00";
        return;
      }
  
      let total = 0;
  
      cartItems.forEach(item => {
        total += item.price * item.quantity;
  
        const itemCard = document.createElement("div");
        itemCard.classList.add("cart-card");
  
        itemCard.innerHTML = `
          <img src="${item.image}" alt="${item.name}">
          <h3 class="product-name">${item.name}</h3>
          <p>Price: $${item.price.toFixed(2)}</p>
          <p>Quantity: ${item.quantity}</p>
          <p>Subtotal: $${(item.price * item.quantity).toFixed(2)}</p>
          <button onclick="removeFromCart(${item.id})">Remove</button>
        `;
  
        cartContainer.appendChild(itemCard);
      });
  
      cartTotal.textContent = `$${total.toFixed(2)}`;
    }
  
    window.removeFromCart = (itemId) => {
      cartItems = cartItems.filter(item => item.id !== itemId);
      localStorage.setItem("cart", JSON.stringify(cartItems));
      renderCart();
    };
  
    window.toggleMenu = () => {
      sidebar.style.left = (sidebar.style.left === "0px") ? "-250px" : "0px";
    };
  
    renderCart();
  });