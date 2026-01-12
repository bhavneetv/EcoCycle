// ===============================
// Leaderboard Initialization
// ===============================
function initLeaderboard() {
    console.log("Leaderboard Initialized ✅");
  
    let confettiManager = null;
  
    // Fetch and render leaderboard data
    async function fetchPointsdetails() {
      const leaderboardTable = document.getElementById("leaderboardTable");
      leaderboardTable.innerHTML = "";
  
      try {
        const response = await fetch("../api/fetchLeaderboard.php");
        const data = await response.json();
  
        if (!data.status) {
          leaderboardTable.innerHTML = `<tr><td colspan="5" class="py-6 text-center text-gray-400">No data found</td></tr>`;
          return;
        }
  
        let rankColor = "text-white";
  
        data.top_users.forEach((element) => {
          // Handle top 3 separately
          if (element.rank === 1) {
            document.getElementById("rank1").textContent = element.full_name;
            document.getElementById("rank1pts").textContent = element.total_points;
            document.getElementById("rank1items").textContent = element.total_bottles;
            rankColor = "text-yellow-400";
          } else if (element.rank === 2) {
            document.getElementById("rank2").textContent = element.full_name;
            document.getElementById("rank2pts").textContent = element.total_points;
            document.getElementById("rank2items").textContent = element.total_bottles;
            rankColor = "text-gray-400";
          } else if (element.rank === 3) {
            document.getElementById("rank3").textContent = element.full_name;
            document.getElementById("rank3pts").textContent = element.total_points;
            document.getElementById("rank3items").textContent = element.total_bottles;
            rankColor = "text-amber-700/20";
          } else {
            rankColor = "text-white";
          }
  
          // Table row
          const row = document.createElement("tr");
          row.className = element.current_user
            ? "border-b border-gray-700/50 hover:bg-white/5 transition-colors bg-green-500/10 border-green-500/30"
            : "border-b border-gray-700/50 hover:bg-white/5 transition-colors";
  
          if (element.current_user) {
            document.getElementById("yourRank").textContent = "#" + element.rank;
          }
  
          row.innerHTML = `
            <td class="py-4 px-6">
              <div class="flex items-center">
                <span class="text-2xl font-bold ${rankColor}">${element.rank}</span>
                ${
                  element.rank <= 3
                    ? `<svg width="64px" class="w-5 h-5 text-yellow-400 ml-2" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M9.15316 5.40838C10.4198 3.13613 11.0531 2 12 2C12.9469 2 13.5802 3.13612 14.8468 5.40837L15.1745 5.99623C15.5345 6.64193 15.7144 6.96479 15.9951 7.17781C16.2757 7.39083 16.6251 7.4699 17.3241 7.62805L17.9605 7.77203C20.4201 8.32856 21.65 8.60682 21.9426 9.54773C22.2352 10.4886 21.3968 11.4691 19.7199 13.4299L19.2861 13.9372C18.8096 14.4944 18.5713 14.773 18.4641 15.1177C18.357 15.4624 18.393 15.8341 18.465 16.5776L18.5306 17.2544C18.7841 19.8706 18.9109 21.1787 18.1449 21.7602C17.3788 22.3417 16.2273 21.8115 13.9243 20.7512L13.3285 20.4768C12.6741 20.1755 12.3469 20.0248 12 20.0248C11.6531 20.0248 11.3259 20.1755 10.6715 20.4768L10.0757 20.7512C7.77268 21.8115 6.62118 22.3417 5.85515 21.7602C5.08912 21.1787 5.21588 19.8706 5.4694 17.2544L5.53498 16.5776C5.60703 15.8341 5.64305 15.4624 5.53586 15.1177C5.42868 14.773 5.19043 14.4944 4.71392 13.9372L4.2801 13.4299C2.60325 11.4691 1.76482 10.4886 2.05742 9.54773C2.35002 8.60682 3.57986 8.32856 6.03954 7.77203L6.67589 7.62805C7.37485 7.4699 7.72433 7.39083 8.00494 7.17781C8.28555 6.96479 8.46553 6.64194 8.82547 5.99623L9.15316 5.40838Z" fill="#e7a358"></path> </g></svg>`
                    : ""
                }
              </div>
            </td>
            <td class="py-4 px-6">
              <div>
                <p class="text-white font-semibold" style="text-transform: capitalize;">${element.full_name}</p>
                <p class="text-gray-400 text-sm">${element.country == "" ? "-" : element.country}</p>
              </div>
            </td>
            <td class="py-4 px-6">
              <div class="text-white font-semibold text-lg">${element.total_points}</div>
              <div class="text-green-400 text-sm">+${element.today_points} today</div>
            </td>
            <td class="py-4 px-6">
              <div class="text-white">${element.total_bottles}</div>
              <div class="text-gray-400 text-sm">+${element.today_bottles} today</div>
            </td>
            <td class="py-4 px-6">
              <span class="${
                element.streak_count >= 10
                  ? "bg-green-500/20 text-green-400"
                  : "bg-orange-500/20 text-orange-400"
              } px-3 py-1 rounded-full text-sm font-medium">
                ${element.streak_count >= 10 ? "🔥" : "🐇"} ${element.streak_count == 0 ?"-" : element.streak_count} days
              </span>
            </td>
          `;
  
          leaderboardTable.appendChild(row);
        });
  
        // Append "You" at the bottom if rank > 7
        function appendYourRow() {
          document.getElementById("yourRank").textContent = "#" + (data.current_user.rank + 1);
          document.getElementById("achievementPoints").textContent = data.current_user.points_to_first;
  
          const row = document.createElement("tr");
          row.className = "border-b border-gray-700/50 hover:bg-white/5 transition-colors bg-green-500/10 border-green-500/30";
          row.innerHTML = `
            <td class="py-4 px-6">
              <div class="flex items-center">
                <span class="text-lg font-semibold text-green-400">${data.current_user.rank + 1}</span>
                <span class="text-xs text-green-400 ml-2">(You)</span>
              </div>
            </td>
            <td class="py-4 px-6">
              <div>
                <p class="text-white font-semibold">${data.current_user.full_name} (You)</p>
                <p class="text-gray-400 text-sm">${data.current_user.country == "" ? "-" : data.current_user.country}</p>
              </div>
            </td>
            <td class="py-4 px-6">
              <div class="text-white font-semibold">${data.current_user.total_points}</div>
              <div class="text-green-400 text-sm">+${data.current_user.today_points} today</div>
            </td>
            <td class="py-4 px-6">
              <div class="text-white">${data.current_user.total_bottles}</div>
              <div class="text-gray-400 text-sm">+${data.current_user.today_bottles} today</div>
            </td>
            <td class="py-4 px-6">
              <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm font-medium">
                ⭐ ${data.current_user.streak_count} days
              </span>
            </td>
          `;
          leaderboardTable.appendChild(row);
        }
  
        if (data.current_user.rank > 7) {
          appendYourRow();
        } else {
          document.getElementById("achievementPoints").textContent = data.current_user.points_to_first;
          if (data.current_user.user_in_top == true) {
            confettiActivation();
          }
        }
      } catch (error) {
        console.error("Error fetching leaderboard:", error);
      }
    }
  
   
    "use strict";

    // Utility functions grouped into a single object
    const Utils = {
      // Parse pixel values to numeric values
      parsePx: (value) => parseFloat(value.replace(/px/, "")),
  
      // Generate a random number between two values, optionally with a fixed precision
      getRandomInRange: (min, max, precision = 0) => {
        const multiplier = Math.pow(10, precision);
        const randomValue = Math.random() * (max - min) + min;
        return Math.floor(randomValue * multiplier) / multiplier;
      },
  
      // Pick a random item from an array
      getRandomItem: (array) => array[Math.floor(Math.random() * array.length)],
  
      // Scaling factor based on screen width
      getScaleFactor: () => Math.log(window.innerWidth) / Math.log(1920),
  
      // Debounce function to limit event firing frequency
      debounce: (func, delay) => {
        let timeout;
        return (...args) => {
          clearTimeout(timeout);
          timeout = setTimeout(() => func(...args), delay);
        };
      },
    };
  
    // Precomputed constants
    const DEG_TO_RAD = Math.PI / 180;
  
    // Centralized configuration for default values
    const defaultConfettiConfig = {
      confettiesNumber: 250,
      confettiRadius: 6,
      confettiColors: [
        "#fcf403", "#62fc03", "#f4fc03", "#03e7fc", "#03fca5", "#a503fc", "#fc03ad", "#fc03c2"
      ],
      emojies: [],
      svgIcon: null, // Example SVG link
    };
  
    // Confetti class representing individual confetti pieces
    class Confetti {
      constructor({ initialPosition, direction, radius, colors, emojis, svgIcon }) {
        const speedFactor = Utils.getRandomInRange(0.9, 1.7, 3) * Utils.getScaleFactor();
        this.speed = { x: speedFactor, y: speedFactor };
        this.finalSpeedX = Utils.getRandomInRange(0.2, 0.6, 3);
        this.rotationSpeed = emojis.length || svgIcon ? 0.01 : Utils.getRandomInRange(0.03, 0.07, 3) * Utils.getScaleFactor();
        this.dragCoefficient = Utils.getRandomInRange(0.0005, 0.0009, 6);
        this.radius = { x: radius, y: radius };
        this.initialRadius = radius;
        this.rotationAngle = direction === "left" ? Utils.getRandomInRange(0, 0.2, 3) : Utils.getRandomInRange(-0.2, 0, 3);
        this.emojiRotationAngle = Utils.getRandomInRange(0, 2 * Math.PI);
        this.radiusYDirection = "down";
  
        const angle = direction === "left" ? Utils.getRandomInRange(82, 15) * DEG_TO_RAD : Utils.getRandomInRange(-15, -82) * DEG_TO_RAD;
        this.absCos = Math.abs(Math.cos(angle));
        this.absSin = Math.abs(Math.sin(angle));
  
        const offset = Utils.getRandomInRange(-150, 0);
        const position = {
          x: initialPosition.x + (direction === "left" ? -offset : offset) * this.absCos,
          y: initialPosition.y - offset * this.absSin
        };
  
        this.position = { ...position };
        this.initialPosition = { ...position };
        this.color = emojis.length || svgIcon ? null : Utils.getRandomItem(colors);
        this.emoji = emojis.length ? Utils.getRandomItem(emojis) : null;
        this.svgIcon = null;
  
        // Preload SVG if provided
        if (svgIcon) {
          this.svgImage = new Image();
          this.svgImage.src = svgIcon;
          this.svgImage.onload = () => {
            this.svgIcon = this.svgImage; // Mark as ready once loaded
          };
        }
  
        this.createdAt = Date.now();
        this.direction = direction;
      }
  
      draw(context) {
        const { x, y } = this.position;
        const { x: radiusX, y: radiusY } = this.radius;
        const scale = window.devicePixelRatio;
  
        if (this.svgIcon) {
          context.save();
          context.translate(scale * x, scale * y);
          context.rotate(this.emojiRotationAngle);
          context.drawImage(this.svgIcon, -radiusX, -radiusY, radiusX * 2, radiusY * 2);
          context.restore();
        } else if (this.color) {
          context.fillStyle = this.color;
          context.beginPath();
          context.ellipse(x * scale, y * scale, radiusX * scale, radiusY * scale, this.rotationAngle, 0, 2 * Math.PI);
          context.fill();
        } else if (this.emoji) {
          context.font = `${radiusX * scale}px serif`;
          context.save();
          context.translate(scale * x, scale * y);
          context.rotate(this.emojiRotationAngle);
          context.textAlign = "center";
          context.fillText(this.emoji, 0, radiusY / 2); // Adjust vertical alignment
          context.restore();
        }
      }
  
      updatePosition(deltaTime, currentTime) {
        const elapsed = currentTime - this.createdAt;
  
        if (this.speed.x > this.finalSpeedX) {
          this.speed.x -= this.dragCoefficient * deltaTime;
        }
  
        this.position.x += this.speed.x * (this.direction === "left" ? -this.absCos : this.absCos) * deltaTime;
        this.position.y = this.initialPosition.y - this.speed.y * this.absSin * elapsed + 0.00125 * Math.pow(elapsed, 2) / 2;
  
        if (!this.emoji && !this.svgIcon) {
          this.rotationSpeed -= 1e-5 * deltaTime;
          this.rotationSpeed = Math.max(this.rotationSpeed, 0);
  
          if (this.radiusYDirection === "down") {
            this.radius.y -= deltaTime * this.rotationSpeed;
            if (this.radius.y <= 0) {
              this.radius.y = 0;
              this.radiusYDirection = "up";
            }
          } else {
            this.radius.y += deltaTime * this.rotationSpeed;
            if (this.radius.y >= this.initialRadius) {
              this.radius.y = this.initialRadius;
              this.radiusYDirection = "down";
            }
          }
        }
      }
  
      isVisible(canvasHeight) {
        return this.position.y < canvasHeight + 100;
      }
    }
  
    class ConfettiManager {
      constructor() {
        this.canvas = document.createElement("canvas");
        this.canvas.style = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; pointer-events: none;";
        document.body.appendChild(this.canvas);
        this.context = this.canvas.getContext("2d");
        this.confetti = [];
        this.lastUpdated = Date.now();
        window.addEventListener("resize", Utils.debounce(() => this.resizeCanvas(), 200));
        this.resizeCanvas();
        requestAnimationFrame(() => this.loop());
      }
  
      resizeCanvas() {
        this.canvas.width = window.innerWidth * window.devicePixelRatio;
        this.canvas.height = window.innerHeight * window.devicePixelRatio;
      }
  
      addConfetti(config = {}) {
        const { confettiesNumber, confettiRadius, confettiColors, emojies, svgIcon } = {
          ...defaultConfettiConfig,
          ...config,
        };
  
        const baseY = (5 * window.innerHeight) / 7;
        for (let i = 0; i < confettiesNumber / 2; i++) {
          this.confetti.push(new Confetti({
            initialPosition: { x: 0, y: baseY },
            direction: "right",
            radius: confettiRadius,
            colors: confettiColors,
            emojis: emojies,
            svgIcon,
          }));
          this.confetti.push(new Confetti({
            initialPosition: { x: window.innerWidth, y: baseY },
            direction: "left",
            radius: confettiRadius,
            colors: confettiColors,
            emojis: emojies,
            svgIcon,
          }));
        }
      }
  
      resetAndStart(config = {}) {
        // Clear existing confetti
        this.confetti = [];
        // Add new confetti
        this.addConfetti(config);
      }
  
      loop() {
        const currentTime = Date.now();
        const deltaTime = currentTime - this.lastUpdated;
        this.lastUpdated = currentTime;
  
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
  
        this.confetti = this.confetti.filter((item) => {
          item.updatePosition(deltaTime, currentTime);
          item.draw(this.context);
          return item.isVisible(this.canvas.height);
        });
  
        requestAnimationFrame(() => this.loop());
      }
    }
  
   // const manager = new ConfettiManager();
    //manager.addConfetti();
    function confettiActivation() {
      if (!confettiManager) confettiManager = new ConfettiManager();
      confettiManager.addConfetti();
    }
  
    // Trigger initial fetch
    fetchPointsdetails();
  }
  
  // ===============================
  // Auto-run when leaderboard page loads
  // ===============================
  if (typeof initLeaderboard === "function") {
    initLeaderboard();
  }
  