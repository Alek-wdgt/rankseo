import React, { useState, useEffect } from 'react';
import { ResponsiveContainer, AreaChart, Area, CartesianGrid, XAxis, YAxis, Tooltip } from 'recharts';

const Dashboard = () => {
    const [statsData, setStatsData] = useState([]);
    const [selectedRange, setSelectedRange] = useState('all');

    useEffect(() => {
        const fetchStatsData = async () => {
            try {
                const response = await fetch('/wp-json/wp/v2/stats');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                setStatsData(data);
            } catch (error) {
                console.error('Error fetching stats data:', error);
            }
        };

        fetchStatsData();
    }, []);

    const chartData = statsData.flatMap(stat => (
        stat.acf.statistics.map(item => ({
            dates: item.dates,
            visitors: item.visitors
        }))
    ));

    const dateRanges = {
        last7: () => {
            const startDate = new Date();
            startDate.setDate(startDate.getDate() - 6);
            return startDate;
        },
        last15: () => {
            const startDate = new Date();
            startDate.setDate(startDate.getDate() - 14);
            return startDate;
        },
        last30: () => {
            const startDate = new Date();
            startDate.setMonth(startDate.getMonth() - 1);
            return startDate;
        },
        all: () => null,
    };

    const filterDataByRange = (range) => {
        const currentDate = new Date();
        const startDate = dateRanges[range] ? dateRanges[range]() : null;

        if (startDate) {
            const filteredData = chartData.filter(item => {
                const itemDate = new Date(item.dates.replace(/(\d{4})(\d{2})(\d{2})/, '$1-$2-$3'));
                return itemDate >= startDate && itemDate <= currentDate;
            });

            return filteredData;
        } else {
            return chartData;
        }
    };

    const formatDate = (dateString) => {
        const [year, month, day] = dateString.match(/(\d{4})(\d{2})(\d{2})/).slice(1);
        return `${day}-${month}-${year}`;
    };

    const handleChangeRange = (event) => {
        setSelectedRange(event.target.value);
    };

    return (
        <div className="widget--container">
            <div className="widget--container__header">
                <div className="widget--container__title">
                    <h2 className="title--main">Graph Widget</h2>
                </div>
                <div className="widget--container__dates">
                    <label htmlFor="rangeSelect">Select Range:</label>
                    <select id="rangeSelect" value={selectedRange} onChange={handleChangeRange}>
                        <option value="all">All-time</option>
                        <option value="last7">Last 7 days</option>
                        <option value="last15">Last 15 days</option>
                        <option value="last30">Last 1 month</option>
                    </select>
                </div>
            </div>

            <ResponsiveContainer width="100%" height={400}>
                <AreaChart
                    data={filterDataByRange(selectedRange)}
                    margin={{ top: 10, right: 0, left: 0, bottom: 0 }}
                >
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis
                        dataKey="dates"
                        tickFormatter={formatDate}
                    />
                    <YAxis />
                    <Tooltip
                        labelFormatter={formatDate}
                        formatter={(value) => [value, `Visitors: ${value}`]}
                    />
                    <Area
                        type="monotone"
                        dataKey="visitors"
                        stroke="#8884d8"
                        fill="#8884d8"
                    />
                </AreaChart>
            </ResponsiveContainer>
        </div>
    );
};

export default Dashboard;
