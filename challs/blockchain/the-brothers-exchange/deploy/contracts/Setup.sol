// SPDX-License-Identifier: UNLICENSED
pragma solidity ^0.8.0;

import "./BudiExchange.sol";
import "./GoodCoin.sol";
import "./WicaksonoETF.sol";

contract Setup {
    BudiExchange public budiExchange;
    GoodCoin public goodCoin;
    WicaksonoETF public wicaksonoETF;

    address player;

    constructor() payable {
        goodCoin = new GoodCoin();
        wicaksonoETF = new WicaksonoETF();
        budiExchange = new BudiExchange(address(goodCoin), address(wicaksonoETF));

        // Mint some initial GoodCoin for the BudiExchange
        goodCoin.mint(address(budiExchange), 2 ether);

        // Fund some initial reward for WicaksonoETF
        wicaksonoETF.fundReward{value: 10 ether}();
    }

    function setPlayer() external {
        require(player == address(0), "Player has been set");
        player = msg.sender;
    }

    function isSolved() external view returns (bool) {
        return goodCoin.balanceOf(player) >= 0.98 ether && player.balance >= 0.98 ether;
    }
}