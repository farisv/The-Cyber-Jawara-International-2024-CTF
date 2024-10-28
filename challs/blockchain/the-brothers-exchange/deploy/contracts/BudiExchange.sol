// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

import "./ERC20.sol";
import "./Ownable.sol";
import "./GoodCoin.sol";
import "./WicaksonoETF.sol";

contract BudiExchange is Ownable {
    GoodCoin public goodCoin;
    WicaksonoETF public wicaksonoETF;
    
    uint256 public exchangeFeePercentage = 5; // 0.5% fee
    uint256 public constant FEE_DENOMINATOR = 1000;
    
    mapping(address => uint256) public lastExchangeTimestamp;
    uint256 public exchangeCooldown = 1 hours;

    event ExchangeCompleted(address indexed user, uint256 sharesAmount, uint256 foreignAmount);
    event FeeUpdated(uint256 newFeePercentage);
    event CooldownUpdated(uint256 newCooldown);

    constructor(address _goodCoin, address _wicaksonoETF) Ownable(msg.sender) {
        goodCoin = GoodCoin(_goodCoin);
        wicaksonoETF = WicaksonoETF(_wicaksonoETF);
    }

    function exchangeSharesForForeign(uint256 shareAmount) external {
        require(lastExchangeTimestamp[msg.sender] == 0 || block.timestamp >= lastExchangeTimestamp[msg.sender] + exchangeCooldown, "Exchange cooldown not met");
        require(wicaksonoETF.transferFrom(msg.sender, address(this), shareAmount), "Transfer failed");
        
        uint256 sharePrice = wicaksonoETF.getSharePrice();
        uint256 foreignAmount = (shareAmount * sharePrice) / 1e18;
        
        uint256 fee = (foreignAmount * exchangeFeePercentage) / FEE_DENOMINATOR;
        uint256 amountAfterFee = foreignAmount - fee;
        
        require(goodCoin.transfer(msg.sender, amountAfterFee), "Foreign currency transfer failed");
        
        lastExchangeTimestamp[msg.sender] = block.timestamp;
        emit ExchangeCompleted(msg.sender, shareAmount, amountAfterFee);
    }

    function setExchangeFee(uint256 _newFeePercentage) external onlyOwner {
        require(_newFeePercentage <= 50, "Fee too high"); // Max 5% fee
        exchangeFeePercentage = _newFeePercentage;
        emit FeeUpdated(_newFeePercentage);
    }

    function setExchangeCooldown(uint256 _newCooldown) external onlyOwner {
        exchangeCooldown = _newCooldown;
        emit CooldownUpdated(_newCooldown);
    }

    function withdrawFees() external onlyOwner {
        uint256 balance = goodCoin.balanceOf(address(this));
        require(balance > 0, "No fees to withdraw");
        require(goodCoin.transfer(owner(), balance), "Fee withdrawal failed");
    }
}