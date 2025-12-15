import React, { useEffect, useState } from "react";
import "../css/app.css";
import { createRoot } from "react-dom/client";
import GeneralDetails from "./components/GeneralDetails";
import Parameters from "./components/Parameters";
import { end } from "@popperjs/core";

const Main = () => {
    const [username, setUsername] = useState("");
    const [token, setToken] = useState("");
    const [endpoint, setEndpoint] = useState("https://api-uat.agmidway.net/");
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState("");
    const [runs, setRuns] = useState([]);
    const [error, setError] = useState("");

    const [casinoGameCode, setCasinoGameCode] = useState("");
    const [liveGameCode, setLiveGameCode] = useState("");
    const [crossGameCode, setCrossGameCode] = useState("");
    const [launchAlias, setLaunchAlias] = useState("");
    const [betPrimary, setBetPrimary] = useState("");
    const [betSecondary, setBetSecondary] = useState("");
    const [winPrimary, setWinPrimary] = useState("");
    const [remoteBonusCodePrimary, setRemoteBonusCodePrimary] = useState("");
    const [bonusInstanceCodePrimary, setBonusInstanceCodePrimary] =
        useState("");
    const [bonusTemplatePrimary, setBonusTemplatePrimary] = useState("");
    const [remoteBonusCodeSecondary, setRemoteBonusCodeSecondary] =
        useState("");
    const [bonusInstanceCodeSecondary, setBonusInstanceCodeSecondary] =
        useState("");
    const [bonusTemplateSecondary, setBonusTemplateSecondary] = useState("");
    const [jackpot, setJackpot] = useState("yes");

    // Live table details
    const [tableId, setTableId] = useState("");
    const [tableName, setTableName] = useState("");

    // Jackpot IDs
    const [jackpotIdMain, setJackpotIdMain] = useState("");
    const [jackpotId110, setJackpotId110] = useState("");
    const [jackpotId120, setJackpotId120] = useState("");
    const [jackpotId130, setJackpotId130] = useState("");
    const [jackpotId140, setJackpotId140] = useState("");

    const handleUsername = async (username) => {
        setUsername(username);
    };

    const handleToken = async (token) => {
        setToken(token);
    };

    const handleEndpoint = async (e) => {
        setEndpoint(e.target.value);
        console.log(endpoint);
    };

    const handleCasinoGameCodeChange = (newCasinoGameCode) => {
        setCasinoGameCode(newCasinoGameCode);
    };

    const handleLiveGameCodeChange = (newLiveGameCode) => {
        setLiveGameCode(newLiveGameCode);
    };

    const handleCrossGameCodeChange = (newCrossGameCode) => {
        setCrossGameCode(newCrossGameCode);
    };

    const handleLaunchAliasChange = (newLaunchAlias) => {
        setLaunchAlias(newLaunchAlias);
    };

    const handleBetPrimaryChange = (newBetPrimary) => {
        setBetPrimary(newBetPrimary);
    };

    const handleBetSecondaryChange = (newBetSecondary) => {
        setBetSecondary(newBetSecondary);
    };

    const handleWinPrimaryChange = (newWinPrimary) => {
        setWinPrimary(newWinPrimary);
    };

    const handleRemoteBonusCodePrimaryChange = (newRemoteBonusCodePrimary) => {
        setRemoteBonusCodePrimary(newRemoteBonusCodePrimary);
    };

    const handleBonusInstanceCodePrimaryChange = (
        newBonusInstanceCodePrimary
    ) => {
        setBonusInstanceCodePrimary(newBonusInstanceCodePrimary);
    };

    const handleBonusTemplatePrimaryChange = (newBonusTemplatePrimary) => {
        setBonusTemplatePrimary(newBonusTemplatePrimary);
    };

    const handleRemoteBonusCodeSecondaryChange = (
        newRemoteBonusCodeSecondary
    ) => {
        setRemoteBonusCodeSecondary(newRemoteBonusCodeSecondary);
    };

    const handleBonusInstanceCodeSecondaryChange = (
        newBonusInstanceCodeSecondary
    ) => {
        setBonusInstanceCodeSecondary(newBonusInstanceCodeSecondary);
    };

    const handleBonusTemplateSecondaryChange = (newBonusTemplateSecondary) => {
        setBonusTemplateSecondary(newBonusTemplateSecondary);
    };

    const handleJackpotChange = async (e) => {
        setJackpot(e.target.value);
    };

    // Live table details handlers
    const handleTableIdChange = (newTableId) => {
        setTableId(newTableId);
    };

    const handleTableNameChange = (newTableName) => {
        setTableName(newTableName);
    };

    // Jackpot ID handlers
    const handleJackpotIdMainChange = (newJackpotIdMain) => {
        setJackpotIdMain(newJackpotIdMain);
    };

    const handleJackpotId110Change = (newJackpotId110) => {
        setJackpotId110(newJackpotId110);
    };

    const handleJackpotId120Change = (newJackpotId120) => {
        setJackpotId120(newJackpotId120);
    };

    const handleJackpotId130Change = (newJackpotId130) => {
        setJackpotId130(newJackpotId130);
    };

    const handleJackpotId140Change = (newJackpotId140) => {
        setJackpotId140(newJackpotId140);
    };

    const fetchRuns = async () => {
        try {
            const res = await fetch("/api/test-runs");
            if (!res.ok) throw new Error("Failed to fetch runs");
            const data = await res.json();
            setRuns(data);
        } catch (e) {
            console.error(e);
            setError("Failed to load previous runs.");
        }
    };

    useEffect(() => {
        fetchRuns();
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setMessage("");
        setError("");

        const payload = {
            username,
            token,
            endpoint,

            // game codes
            casinoGameCode,
            liveGameCode,
            crossGameCode,
            launchAlias,

            // bets & wins
            betPrimary,
            betSecondary,
            winPrimary,

            // bonus primary
            remoteBonusCodePrimary,
            bonusInstanceCodePrimary,
            bonusTemplatePrimary,

            // bonus secondary
            remoteBonusCodeSecondary,
            bonusInstanceCodeSecondary,
            bonusTemplateSecondary,

            // jackpot option
            jackpot,

            // live table details
            tableId,
            tableName,

            // jackpot IDs
            jackpotIdMain,
            jackpotId110,
            jackpotId120,
            jackpotId130,
            jackpotId140,
        };

        try {
            const res = await fetch("/api/test-runs", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload),
            });

            const data = await res.json();

            if (!res.ok) {
                setError(data.message || "Error running tests");
            } else {
                setMessage(
                    `Run created. PHPUnit exit code: ${data.phpunit_exit}.`
                );
                fetchRuns(); // refresh list after successful run
            }
        } catch (e) {
            console.error(e);
            setError("Request failed.");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="container my-5">
            <h1 className="mb-4">API Test Runner</h1>

            {/* SUCCESS MESSAGE */}
            {/* {message && <div className="alert alert-success">{message}</div>} */}

            {/* ERROR MESSAGE */}
            {/* {error && <div className="alert alert-danger">{error}</div>} */}

            {/* FORM */}
            <form onSubmit={handleSubmit} className="mb-4">
                <div className="container">
                    <div className="row">
                        <div className="col">
                            <GeneralDetails
                                username={username}
                                handleUsername={handleUsername}
                                token={token}
                                handleToken={handleToken}
                                endpoint={endpoint}
                                handleEndpoint={handleEndpoint}
                            />
                        </div>
                        <div className="col">
                            <Parameters
                                // game codes
                                casinoGameCode={casinoGameCode}
                                handleCasinoGameCode={
                                    handleCasinoGameCodeChange
                                }
                                liveGameCode={liveGameCode}
                                handleLiveGameCode={handleLiveGameCodeChange}
                                crossGameCode={crossGameCode}
                                handleCrossGameCode={handleCrossGameCodeChange}
                                launchAlias={launchAlias}
                                handleLaunchAlias={handleLaunchAliasChange}
                                // bet / win
                                betPrimary={betPrimary}
                                handleBetPrimary={handleBetPrimaryChange}
                                betSecondary={betSecondary}
                                handleBetSecondary={handleBetSecondaryChange}
                                winPrimary={winPrimary}
                                handleWinPrimary={handleWinPrimaryChange}
                                // primary bonus
                                remoteBonusCodePrimary={remoteBonusCodePrimary}
                                handleRemoteBonusCodePrimary={
                                    handleRemoteBonusCodePrimaryChange
                                }
                                bonusInstanceCodePrimary={
                                    bonusInstanceCodePrimary
                                }
                                handleBonusInstanceCodePrimary={
                                    handleBonusInstanceCodePrimaryChange
                                }
                                bonusTemplatePrimary={bonusTemplatePrimary}
                                handleBonusTemplatePrimary={
                                    handleBonusTemplatePrimaryChange
                                }
                                // secondary bonus
                                remoteBonusCodeSecondary={
                                    remoteBonusCodeSecondary
                                }
                                handleRemoteBonusCodeSecondary={
                                    handleRemoteBonusCodeSecondaryChange
                                }
                                bonusInstanceCodeSecondary={
                                    bonusInstanceCodeSecondary
                                }
                                handleBonusInstanceCodeSecondary={
                                    handleBonusInstanceCodeSecondaryChange
                                }
                                bonusTemplateSecondary={bonusTemplateSecondary}
                                handleBonusTemplateSecondary={
                                    handleBonusTemplateSecondaryChange
                                }
                                // jackpot
                                jackpot={jackpot}
                                handleJackpot={handleJackpotChange}
                                // live table details
                                tableId={tableId}
                                handleTableId={handleTableIdChange}
                                tableName={tableName}
                                handleTableName={handleTableNameChange}
                                // jackpot IDs
                                jackpotIdMain={jackpotIdMain}
                                handleJackpotIdMain={handleJackpotIdMainChange}
                                jackpotId110={jackpotId110}
                                handleJackpotId110={handleJackpotId110Change}
                                jackpotId120={jackpotId120}
                                handleJackpotId120={handleJackpotId120Change}
                                jackpotId130={jackpotId130}
                                handleJackpotId130={handleJackpotId130Change}
                                jackpotId140={jackpotId140}
                                handleJackpotId140={handleJackpotId140Change}
                            />
                            {/* Parameters */}
                        </div>
                    </div>
                </div>

                <button
                    type="submit"
                    className="btn btn-primary"
                    disabled={loading}
                >
                    {loading ? "Running tests…" : "Run Tests"}
                </button>
            </form>

            {/* PREVIOUS RUNS */}
            <h2 className="mt-4">Previous Runs</h2>

            {runs.length === 0 && <p className="text-muted">No runs yet.</p>}

            {runs.length > 0 && (
                <div className="table-responsive">
                    <table className="table table-bordered table-striped">
                        <thead className="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Project ID</th>
                                {/* <th>Exit Code</th> */}
                                <th>Created</th>
                                <th>Report</th>
                            </tr>
                        </thead>
                        <tbody>
                            {runs.map((run) => (
                                <tr key={run.id}>
                                    <td>{run.id}</td>
                                    <td>{run.username}</td>
                                    <td>{run.project_code}</td>
                                    {/* <td>{run.phpunit_exit}</td> */}
                                    <td>{run.created_at}</td>
                                    <td>
                                        <a
                                            href={run.report_url}
                                            target="_blank"
                                            rel="noreferrer"
                                            className="btn btn-sm btn-outline-primary"
                                        >
                                            Open
                                        </a>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
        </div>
    );
};

export default Main;
