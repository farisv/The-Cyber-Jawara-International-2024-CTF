// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

import "./ERC20.sol";
import "./ReentrancyGuard.sol";
import "./Ownable.sol";

contract WicaksonoETF is ERC20, ReentrancyGuard, Ownable {
    uint256 public amountManaged;
    uint256 public feeReceived;
    uint256 public constant FEE_PERCENTAGE = 1; // 1% fee
    uint256 public totalReward;
    mapping(address => uint256) public lastBuyTimestamp;

    constructor() ERC20("WicaksonoETF", "WETF", 18) Ownable(msg.sender) {}

    function buyShares() external payable {
        require(msg.value > 0, "Must send ETH to buy shares");

        uint256 fee = (msg.value * FEE_PERCENTAGE) / 100;
        uint256 amountAfterFee = msg.value - fee;

        feeReceived += fee;
        amountManaged += amountAfterFee;

        uint256 sharesToMint = calculateShares(amountAfterFee);
        _mint(msg.sender, sharesToMint);
        lastBuyTimestamp[msg.sender] = block.timestamp;
    }

    function withdrawShares(uint256 amount) external nonReentrant {
        require(amount > 0, "Amount must be greater than 0");
        require(balanceOf[msg.sender] >= amount, "Insufficient balance");
        uint256 ethToReturn = (amount * amountManaged) / totalSupply;
        _burn(msg.sender, amount);

        uint256 reward = 0;
        if (block.timestamp >= lastBuyTimestamp[msg.sender] + 10 weeks) {
            reward = (totalReward * amount) / totalSupply;
            totalReward -= reward;
        }

        (bool success, ) = msg.sender.call{value: ethToReturn + reward}("");
        require(success, "ETH transfer failed");
        amountManaged -= ethToReturn;
    }

    function calculateShares(uint256 amount) public view returns (uint256) {
        if (totalSupply == 0) {
            return amount;
        }
        return (amount * totalSupply) / amountManaged;
    }

    function getSharePrice() public view returns (uint256) {
        if (totalSupply == 0) {
            return 1 ether;
        }
        return (amountManaged * 1e18) / totalSupply;
    }

    function fundReward() external payable onlyOwner {
        require(msg.value > 0, "Must send ETH to fund reward");
        totalReward += msg.value;
    }
}