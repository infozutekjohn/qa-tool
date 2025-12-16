import React, { useEffect, useState } from "react";
import "../css/app.css";
import GeneralDetails from "./components/GeneralDetails";
import Parameters from "./components/Parameters";
import TestFlow from "./components/TestFlow";

const Main = () => {
    const [username, setUsername] = useState("");
    const [token, setToken] = useState("");
    const [endpoint, setEndpoint] = useState("https://api-uat.agmidway.net/");
    const [loading, setLoading] = useState(false);
    const [runs, setRuns] = useState([]);

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

    const [testGroups, setTestGroups] = useState({
        login: true,
        casino: false,
        live: false,
        bonus: false,
        error: false,
        feature: false,
        logout: false,
    });

    const fetchRuns = async () => {
        const res = await fetch("/api/test-runs");
        const data = await res.json();
        setRuns(data);
    };

    useEffect(() => {
        fetchRuns();
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);

        const payload = {
            username,
            token,
            endpoint,

            testGroups,

            casinoGameCode,
            liveGameCode,
            crossGameCode,
            launchAlias,
            betPrimary,
            betSecondary,
            winPrimary,
            remoteBonusCodePrimary,
            bonusInstanceCodePrimary,
            bonusTemplatePrimary,
            remoteBonusCodeSecondary,
            bonusInstanceCodeSecondary,
            bonusTemplateSecondary,
            jackpot,
        };

        await fetch("/api/test-runs", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload),
        });

        setLoading(false);
        fetchRuns();
    };

    return (
        <div className="container my-5">
            <h1 className="mb-4">API Test Runner</h1>

            <form onSubmit={handleSubmit}>
                <div className="row">
                    <div className="col-md-6">
                        <GeneralDetails
                            username={username}
                            handleUsername={setUsername}
                            token={token}
                            handleToken={setToken}
                            endpoint={endpoint}
                            handleEndpoint={(e) => setEndpoint(e.target.value)}
                        />
                        <TestFlow
                            testGroups={testGroups}
                            setTestGroups={setTestGroups}
                        />
                    </div>

                    <div className="col-md-6">
                        <Parameters
                            casinoGameCode={casinoGameCode}
                            handleCasinoGameCode={setCasinoGameCode}
                            liveGameCode={liveGameCode}
                            handleLiveGameCode={setLiveGameCode}
                            crossGameCode={crossGameCode}
                            handleCrossGameCode={setCrossGameCode}
                            launchAlias={launchAlias}
                            handleLaunchAlias={setLaunchAlias}
                            betPrimary={betPrimary}
                            handleBetPrimary={setBetPrimary}
                            betSecondary={betSecondary}
                            handleBetSecondary={setBetSecondary}
                            winPrimary={winPrimary}
                            handleWinPrimary={setWinPrimary}
                            remoteBonusCodePrimary={remoteBonusCodePrimary}
                            handleRemoteBonusCodePrimary={
                                setRemoteBonusCodePrimary
                            }
                            bonusInstanceCodePrimary={bonusInstanceCodePrimary}
                            handleBonusInstanceCodePrimary={
                                setBonusInstanceCodePrimary
                            }
                            bonusTemplatePrimary={bonusTemplatePrimary}
                            handleBonusTemplatePrimary={setBonusTemplatePrimary}
                            remoteBonusCodeSecondary={remoteBonusCodeSecondary}
                            handleRemoteBonusCodeSecondary={
                                setRemoteBonusCodeSecondary
                            }
                            bonusInstanceCodeSecondary={
                                bonusInstanceCodeSecondary
                            }
                            handleBonusInstanceCodeSecondary={
                                setBonusInstanceCodeSecondary
                            }
                            bonusTemplateSecondary={bonusTemplateSecondary}
                            handleBonusTemplateSecondary={
                                setBonusTemplateSecondary
                            }
                            jackpot={jackpot}
                            handleJackpot={(e) => setJackpot(e.target.value)}
                        />
                    </div>
                </div>

                <button className="btn btn-primary mt-3" disabled={loading}>
                    {loading ? "Running…" : "Run Tests"}
                </button>
            </form>

            <hr />

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
