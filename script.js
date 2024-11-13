const apiKey = "coinranking1e8e692a0bb989ddc76121d0320703d981bc91a5faa21ff3"; 
const url = "https://api.coinranking.com/v2/coins";

fetch(url, {
    method: "GET",
    headers: {
        'Content-Type': 'application/json',
        'x-access-token': apiKey
    }
})
.then(response => {
    if (!response.ok) throw new Error("no response");
    return response.json();
})
.then(data => {
    dropdown(data.data.coins);
})
.catch(error => console.error("Error:", error));

function dropdown(cryptoData) {
    const dropdown = document.getElementById('coins');

    cryptoData.forEach(coin => {
        const option = document.createElement('option');
        option.value = coin.symbol;
        option.innerText = coin.name;
        dropdown.appendChild(option);
    });
 

    //  displayCoinInfo(cryptoData[0]);

            // Add event listener for selection changes
            dropdown.addEventListener("change", () => {
                const selectedCoinSymbol = dropdown.value;
                const selectedCoin = cryptoData.find(coin => coin.symbol === selectedCoinSymbol);
                displayCoinInfo(selectedCoin);
            });
        }

        function displayCoinInfo(coin) {
            const infoDiv = document.getElementById("crypto-info");
            infoDiv.innerHTML = `
                <h2>${coin.name} (${coin.symbol})</h2>
                <p>Price:  $${Math.round(coin.price)}</p>
                <p>Market Cap: $${coin.marketCap.toLocaleString()}</p>
                <p>Date listed: ${new Date(coin.listedAt * 1000).toLocaleDateString()}</p>
                <p>Rank: ${coin.rank}</p>
                <a href="${coin.coinrankingUrl}" target="_blank">Visit Website</a>
            `;
        }    