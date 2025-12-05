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
        </div>
    );
};

export default Parameters;
