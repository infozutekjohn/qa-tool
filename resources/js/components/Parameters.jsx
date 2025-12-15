import React from "react";

const Parameters = ({
    casinoGameCode,
    handleCasinoGameCode,
    liveGameCode,
    handleLiveGameCode,
    crossGameCode,
    handleCrossGameCode,
    launchAlias,
    handleLaunchAlias,
    tableId,
    handleTableId,
    tableName,
    handleTableName,
    betPrimary,
    handleBetPrimary,
    betSecondary,
    handleBetSecondary,
    winPrimary,
    handleWinPrimary,
    remoteBonusCodePrimary,
    handleRemoteBonusCodePrimary,
    bonusInstanceCodePrimary,
    handleBonusInstanceCodePrimary,
    bonusTemplatePrimary,
    handleBonusTemplatePrimary,
    remoteBonusCodeSecondary,
    handleRemoteBonusCodeSecondary,
    bonusInstanceCodeSecondary,
    handleBonusInstanceCodeSecondary,
    bonusTemplateSecondary,
    handleBonusTemplateSecondary,
    jackpot,
    handleJackpot,
    jackpotIdMain,
    handleJackpotIdMain,
    jackpotId110,
    handleJackpotId110,
    jackpotId120,
    handleJackpotId120,
    jackpotId130,
    handleJackpotId130,
    jackpotId140,
    handleJackpotId140,
}) => {
    return (
        <div className="container">
            {/* Game Code (casino) */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Game Code (casino)</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={casinoGameCode}
                        onChange={(e) => handleCasinoGameCode(e.target.value)}
                        className="form-control"
                    />
                </div>
            </div>

            {/* Game Code (live) */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Game Code (live)</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={liveGameCode}
                        onChange={(e) => handleLiveGameCode(e.target.value)}
                        className="form-control"
                    />
                </div>
            </div>

            {/* Game Code (cross launch) */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">
                        Game Code (cross launch)
                    </label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={crossGameCode}
                        onChange={(e) => handleCrossGameCode(e.target.value)}
                        className="form-control"
                    />
                </div>
            </div>

            {/* Launch alias */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Launch alias</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={launchAlias}
                        onChange={(e) => handleLaunchAlias(e.target.value)}
                        className="form-control"
                    />
                </div>
            </div>

            {/* Table ID */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Table ID</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={tableId}
                        onChange={(e) => handleTableId(e.target.value)}
                        className="form-control"
                        placeholder="1234"
                    />
                </div>
            </div>

            {/* Table Name */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Table Name</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={tableName}
                        onChange={(e) => handleTableName(e.target.value)}
                        className="form-control"
                        placeholder="Integration Test"
                    />
                </div>
            </div>

            {/* Bet Amount 1 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Bet Amount 1</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={betPrimary}
                        onChange={(e) => handleBetPrimary(e.target.value)}
                        className="form-control"
                        required
                    />
                </div>
            </div>

            {/* Bet Amount 2 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Bet Amount 2</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={betSecondary}
                        onChange={(e) => handleBetSecondary(e.target.value)}
                        className="form-control"
                        required
                    />
                </div>
            </div>

            {/* Win Amount */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Win Amount</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={winPrimary}
                        onChange={(e) => handleWinPrimary(e.target.value)}
                        className="form-control"
                        required
                    />
                </div>
            </div>

            {/* Remote Bonus Code 1 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Remote Bonus Code 1</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={remoteBonusCodePrimary}
                        onChange={(e) =>
                            handleRemoteBonusCodePrimary(e.target.value)
                        }
                        className="form-control"
                    />
                </div>
            </div>

            {/* Bonus Instance Code 1 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Bonus Instance Code 1</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={bonusInstanceCodePrimary}
                        onChange={(e) =>
                            handleBonusInstanceCodePrimary(e.target.value)
                        }
                        className="form-control"
                    />
                </div>
            </div>

            {/* Bonus Template ID 1 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Bonus Template ID 1</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={bonusTemplatePrimary}
                        onChange={(e) =>
                            handleBonusTemplatePrimary(e.target.value)
                        }
                        className="form-control"
                    />
                </div>
            </div>

            {/* Remote Bonus Code 2 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Remote Bonus Code 2</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={remoteBonusCodeSecondary}
                        onChange={(e) =>
                            handleRemoteBonusCodeSecondary(e.target.value)
                        }
                        className="form-control"
                    />
                </div>
            </div>

            {/* Bonus Instance Code 2 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Bonus Instance Code 2</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={bonusInstanceCodeSecondary}
                        onChange={(e) =>
                            handleBonusInstanceCodeSecondary(e.target.value)
                        }
                        className="form-control"
                    />
                </div>
            </div>

            {/* Bonus Template ID 2 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Bonus Template ID 2</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={bonusTemplateSecondary}
                        onChange={(e) =>
                            handleBonusTemplateSecondary(e.target.value)
                        }
                        className="form-control"
                    />
                </div>
            </div>

            {/* Jackpot Included */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Jackpot Included</label>
                </div>
                <div className="col-8">
                    <select
                        className="form-select"
                        aria-label="Jackpot included"
                        value={jackpot}
                        onChange={handleJackpot}
                    >
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>

            {/* Jackpot ID Main */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Jackpot ID (Main)</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={jackpotIdMain}
                        onChange={(e) => handleJackpotIdMain(e.target.value)}
                        className="form-control"
                        placeholder="test_110_120_130_140_333"
                    />
                </div>
            </div>

            {/* Jackpot ID 110 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Jackpot ID (110)</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={jackpotId110}
                        onChange={(e) => handleJackpotId110(e.target.value)}
                        className="form-control"
                        placeholder="test_110_333"
                    />
                </div>
            </div>

            {/* Jackpot ID 120 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Jackpot ID (120)</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={jackpotId120}
                        onChange={(e) => handleJackpotId120(e.target.value)}
                        className="form-control"
                        placeholder="test_120_333"
                    />
                </div>
            </div>

            {/* Jackpot ID 130 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Jackpot ID (130)</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={jackpotId130}
                        onChange={(e) => handleJackpotId130(e.target.value)}
                        className="form-control"
                        placeholder="test_130_333"
                    />
                </div>
            </div>

            {/* Jackpot ID 140 */}
            <div className="row mb-3">
                <div className="col-4">
                    <label className="form-label">Jackpot ID (140)</label>
                </div>
                <div className="col-8">
                    <input
                        type="text"
                        value={jackpotId140}
                        onChange={(e) => handleJackpotId140(e.target.value)}
                        className="form-control"
                        placeholder="test_140_333"
                    />
                </div>
            </div>
        </div>
    );
};

export default Parameters;
