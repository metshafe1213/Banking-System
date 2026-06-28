document.addEventListener("DOMContentLoaded", function(){

    // active sidebar link
    const currentPage = window.location.pathname.split("/").pop();
    const links = document.querySelectorAll("#sidebar nav a");
    links.forEach(link => {
        if(link.getAttribute("href") === currentPage){
            link.classList.add("active");
        }
    });

    // dashboard code
    if(document.getElementById("transactionsBox")){
        fetch("dashboard.php")
            .then(res => res.json())
            .then(data => {
                document.getElementById("fullNameValue").innerText = data.fullName;
                document.getElementById("accountNumberValue").innerText = data.accountNumber;
                document.getElementById("balanceValue").innerText = data.balance + " ETB";

                if(data.transactions.length > 0){
                    let table = `<table>
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    data.transactions.forEach(trans => {
                        table += `<tr>
                            <td>${trans.type}</td>
                            <td>${trans.amount} ETB</td>
                            <td>${trans.date}</td>
                        </tr>`;
                    });
                    table += `</tbody></table>`;
                    document.getElementById("transactionsList").innerHTML = table;
                } else {
                    document.getElementById("transactionsList").innerHTML = "<p>No transactions yet.</p>";
                }

                document.getElementById("transactionsBox").onclick = function(){
                    window.location.href = "transactions.html";
                }
                document.getElementById("depositBox").onclick = function(){
                    window.location.href = "deposit.html";
                }
                document.getElementById("withdrawBox").onclick = function(){
                    window.location.href = "withdraw.html";
                }
                document.getElementById("transferBox").onclick = function(){
                    window.location.href = "transfer.html";
                }
            });
    }

    // deposit page code
    if(document.getElementById("depositForm")){
        fetch("dashboard.php")
            .then(res => res.json())
            .then(data => {
                document.getElementById("currentBalance").innerText = data.balance + " ETB";
            });
    }

    // withdraw page code
    if(document.getElementById("withdrawForm")){
        fetch("dashboard.php")
            .then(res => res.json())
            .then(data => {
                document.getElementById("currentBalance").innerText = data.balance + " ETB";
            });
    }

    // transfer page code
    if(document.getElementById("transferForm")){
        fetch("dashboard.php")
            .then(res => res.json())
            .then(data => {
                document.getElementById("currentBalance").innerText = data.balance + " ETB";
            });
    }

    // transactions page code
    if(document.getElementById("transactionsPage")){
        fetch("transactions.php")
            .then(res => res.json())
            .then(data => {
                if(data.transactions.length > 0){
                    data.transactions.forEach(trans => {
                        document.getElementById("transactionsList").innerHTML +=
                        `<tr>
                            <td>${trans.type}</td>
                            <td>${trans.amount} ETB</td>
                            <td>${trans.description}</td>
                            <td>${trans.date}</td>
                        </tr>`;
                    });
                } else {
                    document.getElementById("transactionsList").innerHTML =
                    "<tr><td colspan='4'>No transactions yet.</td></tr>";
                }
            });
    }

    // url params for errors and success messages
    const params = new URLSearchParams(window.location.search);
    if(params.get("error") === "nomatch") alert("Passwords do not match");
    if(params.get("error") === "failed") alert("Registration failed");
    if(params.get("error") === "taken") alert("Username already taken");
    if(params.get("error") === "wrongpassword") alert("Invalid password");
    if(params.get("error") === "notfound") alert("User not found");
    if(params.get("success") === "registered") alert("Account created! Please login.");

});

function submitDeposit(){
    const amount = document.getElementById("depositAmount").value;
    fetch("deposit.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ amount: amount })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert("Deposit successful!");
            document.getElementById("currentBalance").innerText = data.newBalance + " ETB";
        } else {
            alert("Deposit failed: " + data.error);
        }
    });
}

function submitWithdraw(){
    const amount = document.getElementById("withdrawAmount").value;
    fetch("withdraw.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ amount: amount })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert("Withdrawal successful!");
            document.getElementById("currentBalance").innerText = data.newBalance + " ETB";
        } else {
            alert("Withdrawal failed: " + data.error);
        }
    });
}

function submitTransfer(){
    const amount = document.getElementById("transferAmount").value;
    const recipientAccount = document.getElementById("recipientAccount").value;
    fetch("transfer.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ amount: amount, recipientAccount: recipientAccount })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert("Transfer successful!");
            document.getElementById("currentBalance").innerText = data.newBalance + " ETB";
        } else {
            alert("Transfer failed: " + data.error);
        }
    });
}

function logout(){
    window.location.href = "logout.php";
}
